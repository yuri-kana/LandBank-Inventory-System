<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Notifications\LandBankVerifyEmailNotification;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $teams = Team::with(['users'])->get();
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        // Validate ONLY the team name - no user fields needed here
        $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,name',
        ], [
            'team_name.required' => 'The team name field is required.',
            'team_name.max' => 'Team name cannot exceed 255 characters.',
            'team_name.unique' => 'A team with this name already exists.'
        ]);

        try {
            DB::beginTransaction();

            // Create only the team (no users yet)
            $team = Team::create([
                'name' => $request->team_name,
            ]);

            DB::commit();

            return redirect()->route('admin.teams.index')
                ->with('success', 'Team "' . $team->name . '" created successfully! You can now add members to this team.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Team creation failed: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Failed to create team. Please try again.']);
        }
    }

    public function destroy(Team $team)
    {
        try {
            DB::beginTransaction();
            
            $team->users()->delete();
            $team->delete();

            DB::commit();

            return redirect()->route('teams.index')
                ->with('success', 'Team and associated users deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Team deletion failed: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to delete team. Please try again.');
        }
    }

    public function getMembers(Team $team)
    {
        Log::info('Getting members for team ID: ' . $team->id);
        
        try {
            $members = $team->users()->select(
                'id', 
                'name', 
                'email', 
                'is_active', 
                'email_verified_at', 
                'password_change_required',
                'profile_photo_path'
            )->get();
            
            Log::info('Found ' . $members->count() . ' members for team: ' . $team->id);
            
            return response()->json($members);
            
        } catch (\Exception $e) {
            Log::error('Error getting team members: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load members'], 500);
        }
    }

    public function addMember(Request $request)
    {
        Log::info('Add member request received:', $request->all());
        
        $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        try {
            DB::beginTransaction();

            $team = Team::findOrFail($request->team_id);
            
            Log::info('Adding member to team: ' . $team->id . ' - ' . $team->name);
            
            $verificationToken = Str::random(60);
            
            $teamNumber = $team->id;
            $currentYear = date('Y');
            $teamPassword = "Inventory-Team{$teamNumber}@{$currentYear}";

            Log::info('Creating user with email: ' . $request->email);
            Log::info('Team password will be: ' . $teamPassword);
            
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($teamPassword),
                'team_id' => $team->id,
                'role' => 'team_member',
                'verification_token' => hash('sha256', $verificationToken),
                'is_active' => false,
                'email_verified_at' => null,
                'verification_required' => true,
                'verification_sent_at' => now(),
                'password_change_required' => true,
            ]);

            Log::info('User created with ID: ' . $user->id);
            
            try {
                // FIXED: Pass team ID as second parameter in constructor
                $user->notify(new LandBankVerifyEmailNotification($verificationToken, $team->id));
                Log::info('Verification email sent to: ' . $user->email . ' with team ID: ' . $team->id);
            } catch (\Exception $mailError) {
                Log::error('Email sending failed: ' . $mailError->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invitation sent successfully to ' . $request->email,
                'user_id' => $user->id,
                'team_id' => $team->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add member failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeMember(Team $team, User $user)
    {
        Log::info('Remove member request - Team: ' . $team->id . ', User: ' . $user->id);
        
        try {
            if ($user->team_id !== $team->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not belong to this team'
                ], 400);
            }

            $user->delete();
            
            Log::info('User removed successfully');

            return response()->json([
                'success' => true,
                'message' => 'Member removed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Remove member failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove member: ' . $e->getMessage()
            ], 500);
        }
    }
}