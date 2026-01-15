<!-- resources/views/teams/index.blade.php -->
@section('title', 'Teams - Inventory System')

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Teams Management') }}
            </h2>
            <a href="{{ route('admin.teams.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Add New Team
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Teams Grid with improved responsive design -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($teams as $team)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
                                <!-- Team Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate" title="{{ $team->name }}">
                                            {{ $team->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-users mr-1"></i> 
                                            {{ $team->users->count() }} member{{ $team->users->count() !== 1 ? 's' : '' }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2 ml-2 flex-shrink-0">
                                        <!-- View Team Members Button with icon -->
                                        <button type="button" 
                                                onclick="openManageMembersModal('{{ $team->id }}', '{{ $team->name }}', {{ $team->users->count() }})"
                                                class="text-blue-600 hover:text-blue-900 p-1"
                                                title="View Team Members">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <!-- Delete Button (Only shown if team has no members) -->
                                        @if($team->users->count() == 0)
                                            <button type="button" 
                                                    onclick="openDeleteTeamModal('{{ $team->id }}', '{{ $team->name }}')"
                                                    class="text-red-600 hover:text-red-900 p-1"
                                                    title="Delete Team">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Team Members Section -->
                                @if($team->users->count() > 0)
                                    <div class="border-t border-gray-100 pt-4 flex-grow">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Team Members:</h4>
                                        <ul class="space-y-3">
                                            @foreach($team->users as $user)
                                                <li class="flex items-center justify-between">
                                                    <div class="flex items-center flex-1 min-w-0">
                                                        <!-- Profile Photo - Clickable -->
                                                        <div class="relative flex-shrink-0 mr-3 cursor-pointer" 
                                                             onclick="viewProfilePhoto('{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=4f46e5&color=ffffff&size=512" }}', '{{ $user->name }}')">
                                                            @if($user->profile_photo_path)
                                                                <img src="{{ Storage::url($user->profile_photo_path) }}" 
                                                                    alt="{{ $user->name }}"
                                                                    class="h-8 w-8 rounded-full object-cover border border-gray-200 hover:border-blue-500 transition duration-200"
                                                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4f46e5&color=ffffff&size=32'">
                                                            @else
                                                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold hover:bg-blue-600 transition duration-200">
                                                                    {{ $user->initials }}
                                                                </div>
                                                            @endif
                                                            
                                                            <!-- Status indicator -->
                                                            @if($user->hasVerifiedEmail() && $user->is_active)
                                                                <div class="absolute -bottom-1 -right-1 h-3 w-3 bg-green-500 rounded-full border-2 border-white"></div>
                                                            @elseif(!$user->hasVerifiedEmail())
                                                                <div class="absolute -bottom-1 -right-1 h-3 w-3 bg-yellow-500 rounded-full border-2 border-white"></div>
                                                            @elseif(!$user->is_active)
                                                                <div class="absolute -bottom-1 -right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></div>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center flex-wrap gap-1">
                                                                    <span class="text-sm text-gray-900 font-medium truncate" title="{{ $user->name }}">
                                                                        {{ $user->name }}
                                                                    </span>
                                                                    <span class="text-xs text-gray-500 truncate" title="{{ $user->email }}">
                                                                        ({{ $user->email }})
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-1">
                                                                @if(!$user->hasVerifiedEmail())
                                                                    <span class="inline-flex items-center text-xs text-yellow-600 bg-yellow-50 px-2 py-1 rounded">
                                                                        <i class="fas fa-envelope mr-1 text-xs"></i>Invited
                                                                    </span>
                                                                @elseif(!$user->is_active)
                                                                    <span class="inline-flex items-center text-xs text-red-600 bg-red-50 px-2 py-1 rounded">
                                                                        <i class="fas fa-ban mr-1 text-xs"></i>Inactive
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center text-xs text-green-600 bg-green-50 px-2 py-1 rounded">
                                                                        <i class="fas fa-check mr-1 text-xs"></i>Active
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="border-t border-gray-100 pt-4 flex-grow">
                                        <p class="text-sm text-gray-500 italic">No members assigned to this team</p>
                                    </div>
                                @endif

                                <!-- Footer with creation date -->
                                <div class="border-t border-gray-100 pt-4 mt-4">
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        Created {{ $team->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($teams->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Teams Yet</h3>
                            <p class="text-gray-500 mb-4">Create your first team to get started</p>
                            <a href="{{ route('admin.teams.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Add New Team
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Team Modal -->
    <div id="deleteTeamModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-[100]">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <!-- Warning Icon -->
                <div class="flex justify-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.794-.833-2.564 0L5.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Team</h3>
                    
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete 
                            <span id="deleteTeamName" class="font-semibold text-red-600"></span>?
                        </p>
                        <p class="text-xs text-red-500 font-medium mt-2">
                            ⚠️ This action cannot be undone.
                        </p>
                    </div>
                    
                    <!-- Delete Form -->
                    <form id="deleteTeamForm" method="POST" class="mt-6">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-center space-x-3">
                            <button type="button" 
                                    onclick="closeDeleteTeamModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                                Cancel
                            </button>
                            
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                                Delete Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Member Modal -->
    <div id="deleteMemberModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-[100]">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <!-- Warning Icon -->
                <div class="flex justify-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.794-.833-2.564 0L5.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Modal Content -->
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Remove Team Member</h3>
                    
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to remove 
                            <span id="deleteMemberName" class="font-semibold text-red-600"></span> 
                            from the team?
                        </p>
                        <p class="text-xs text-red-500 font-medium mt-2">
                            ⚠️ This action cannot be undone.
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            The member will no longer have access to this team.
                        </p>
                    </div>
                    
                    <!-- Delete Form -->
                    <form id="deleteMemberForm" method="POST" class="mt-6">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-center space-x-3">
                            <button type="button" 
                                    onclick="closeDeleteMemberModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                                Cancel
                            </button>
                            
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                                Remove Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Members Modal -->
    <div id="manageMembersModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-[50]">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-6 flex-shrink-0">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 truncate" id="modalTitle">Team Members</h3>
                        <p class="text-sm text-gray-500 mt-1" id="teamMemberCount">
                            <i class="fas fa-users mr-1"></i> 
                            <!-- Member count will be inserted here -->
                        </p>
                    </div>
                    <button type="button" onclick="closeManageMembersModal()" class="text-gray-400 hover:text-gray-500 flex-shrink-0 ml-4">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Team Name</label>
                        <p class="text-gray-900 font-medium truncate text-lg" id="teamNameDisplay"></p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800 mb-1">Default Team Password System</h4>
                                <p class="text-xs text-blue-700">
                                    New members will receive a team-specific default password via email. 
                                    The default password follows this format: <code class="bg-blue-100 px-1 py-0.5 rounded">Inventory-Team{team_id}@2026</code>
                                </p>
                                <p class="text-xs text-blue-700 mt-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Members must change their password on first login for security.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two-column layout for Existing Members and Add Member form -->
            <div class="flex flex-col md:flex-row flex-grow overflow-hidden">
                <!-- Existing Members List with scroll -->
                <div class="w-full md:w-1/2 border-t md:border-r border-gray-200 p-6 overflow-hidden flex flex-col">
                    <div class="flex justify-between items-center mb-3 flex-shrink-0">
                        <h4 class="text-sm font-medium text-gray-700">Existing Members</h4>
                        <span class="text-xs text-gray-500" id="memberCountBadge"></span>
                    </div>
                    <div id="existingMembersList" class="space-y-2 overflow-y-auto flex-grow pr-2">
                        <p class="text-sm text-gray-500 italic">Loading members...</p>
                    </div>
                </div>

                <!-- Add Member Form -->
                <div class="w-full md:w-1/2 border-t border-gray-200 p-6 overflow-hidden flex flex-col">
                    <div class="flex justify-between items-center mb-3 flex-shrink-0">
                        <h4 class="text-sm font-medium text-gray-700">Add New Member</h4>
                        <button type="button" onclick="clearAddMemberForm()" class="text-xs text-gray-500 hover:text-gray-700" title="Clear form">
                            <i class="fas fa-redo"></i> Clear
                        </button>
                    </div>
                    
                    <div class="flex-grow overflow-y-auto pr-2">
                        <form id="addMemberForm" method="POST">
                            @csrf
                            <input type="hidden" id="teamId" name="team_id">
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" 
                                       id="name" 
                                       name="name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter a Name"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Enter the full name of the team member</p>
                            </div>
                            
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" 
                                       id="email" 
                                       name="email"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter a valid email address"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">
                                    An invitation email with default team password will be sent to this address
                                </p>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" 
                                        onclick="closeManageMembersModal()" 
                                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                                    Cancel
                                </button>
                                <button type="button" 
                                        onclick="sendInvitation()"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-paper-plane mr-2"></i>Send Invitation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Photo Viewer Modal -->
    <div id="photoViewerModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-[200]">
        <!-- Close button (X icon) -->
        <button type="button" 
                onclick="closePhotoViewer()"
                class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10 transition duration-200 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Photo container -->
        <div class="relative w-full h-full flex items-center justify-center p-4">
            <img id="viewerPhoto" 
                 src="" 
                 alt="Profile Photo"
                 class="max-w-full max-h-full object-contain rounded-lg">
        </div>
        
        <!-- Photo name (optional) -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg text-sm">
            <span id="viewerPhotoName"></span>
        </div>
    </div>

    <script>
    let currentTeamId = '';
    let lastOpenedTeamInfo = null;
    
    // Delete Team Modal Functions
    function openDeleteTeamModal(teamId, teamName) {
        // Close manage members modal if open
        if (!document.getElementById('manageMembersModal').classList.contains('hidden')) {
            closeManageMembersModal();
        }
        
        document.getElementById('deleteTeamName').textContent = teamName;
        const form = document.getElementById('deleteTeamForm');
        form.action = `/admin/teams/${teamId}`;
        
        const modal = document.getElementById('deleteTeamModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteTeamModal() {
        const modal = document.getElementById('deleteTeamModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Delete Member Modal Functions - UPDATED
    function openDeleteMemberModal(teamId, memberId, memberName) {
        // Store current team info before closing
        if (!document.getElementById('manageMembersModal').classList.contains('hidden')) {
            lastOpenedTeamInfo = {
                teamId: currentTeamId,
                teamName: document.getElementById('teamNameDisplay').textContent,
                memberCount: parseInt(document.getElementById('teamMemberCount').textContent.match(/\d+/)[0]) || 0
            };
            closeManageMembersModal();
        }
        
        // Small delay to ensure modal closes completely
        setTimeout(() => {
            document.getElementById('deleteMemberName').textContent = memberName;
            const form = document.getElementById('deleteMemberForm');
            form.action = `/admin/teams/${teamId}/remove-member/${memberId}`;
            
            const modal = document.getElementById('deleteMemberModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }, 100);
    }

    function closeDeleteMemberModal() {
        const modal = document.getElementById('deleteMemberModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        // Reopen manage members modal after delete (with updated count)
        if (lastOpenedTeamInfo) {
            setTimeout(() => {
                openManageMembersModal(
                    lastOpenedTeamInfo.teamId,
                    lastOpenedTeamInfo.teamName,
                    lastOpenedTeamInfo.memberCount - 1 // Decrease count by 1
                );
                lastOpenedTeamInfo = null;
            }, 300);
        }
    }
    
    function openManageMembersModal(teamId, teamName, memberCount) {
        currentTeamId = teamId;
        document.getElementById('teamId').value = teamId;
        document.getElementById('teamNameDisplay').textContent = teamName;
        document.getElementById('modalTitle').textContent = `Team Members - ${teamName}`;
        
        const memberCountText = `${memberCount} member${memberCount !== 1 ? 's' : ''}`;
        document.getElementById('teamMemberCount').innerHTML = `<i class="fas fa-users mr-1"></i> ${memberCountText}`;
        document.getElementById('memberCountBadge').textContent = memberCountText;
        
        document.getElementById('manageMembersModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        loadExistingMembers(teamId);
    }

    function closeManageMembersModal() {
        document.getElementById('manageMembersModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('addMemberForm').reset();
        lastOpenedTeamInfo = null;
    }

    function clearAddMemberForm() {
        document.getElementById('addMemberForm').reset();
    }

    // AJAX form submission for delete member
    document.getElementById('deleteMemberForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Removing...';
        submitBtn.disabled = true;
        
        fetch(form.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message || 'Member removed successfully!');
                closeDeleteMemberModal();
                
                // Reload the page to update the team list
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('error', data.message || 'Failed to remove member.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while removing the member.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // AJAX form submission for delete team
    document.getElementById('deleteTeamForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
        submitBtn.disabled = true;
        
        fetch(form.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json().catch(() => ({ success: true }));
            }
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        })
        .then(data => {
            showAlert('success', 'Team deleted successfully!');
            closeDeleteTeamModal();
            
            // Reload the page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Failed to delete team.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    function loadExistingMembers(teamId) {
        const membersList = document.getElementById('existingMembersList');
        membersList.innerHTML = '<p class="text-sm text-gray-500 italic">Loading members...</p>';
        
        fetch(`/admin/teams/${teamId}/members`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Response text:', text);
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Loaded members data:', data);
            if (data && data.length > 0) {
                let html = '';
                data.forEach(member => {
                    const statusClass = !member.email_verified_at ? 'text-yellow-600 bg-yellow-50' : 
                                    (!member.is_active ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50');
                    const statusText = !member.email_verified_at ? 'Invited' : 
                                    (!member.is_active ? 'Inactive' : 'Active');
                    const statusIcon = !member.email_verified_at ? 'fa-envelope' : 
                                    (!member.is_active ? 'fa-ban' : 'fa-check');
                    
                    const getInitials = (name) => {
                        if (!name) return 'U';
                        return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                    };
                    
                    let profilePhotoHtml = '';
                    if (member.profile_photo_path) {
                        profilePhotoHtml = `
                            <img src="/storage/${member.profile_photo_path}" 
                                alt="${member.name || member.email}"
                                class="h-10 w-10 rounded-full object-cover border border-gray-200 cursor-pointer hover:border-blue-500 transition duration-200"
                                onclick="viewProfilePhoto('/storage/${member.profile_photo_path}', '${member.name || member.email}')"
                                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(member.name || member.email)}&background=4f46e5&color=ffffff&size=512'">
                        `;
                    } else {
                        const initials = getInitials(member.name);
                        profilePhotoHtml = `
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold cursor-pointer hover:bg-blue-600 transition duration-200"
                                 onclick="viewProfilePhoto('https://ui-avatars.com/api/?name=${encodeURIComponent(member.name || member.email)}&background=4f46e5&color=ffffff&size=512', '${member.name || member.email}')">
                                ${initials}
                            </div>
                        `;
                    }
                    
                    const statusColor = !member.email_verified_at ? 'bg-yellow-500' : 
                                    (!member.is_active ? 'bg-red-500' : 'bg-green-500');
                    
                    html += `
                        <div class="member-item flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition duration-150">
                            <div class="flex items-center flex-1 min-w-0">
                                <div class="flex-shrink-0 mr-3 relative">
                                    ${profilePhotoHtml}
                                    <div class="absolute -bottom-1 -right-1 h-3 w-3 ${statusColor} rounded-full border-2 border-white"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="text-gray-900 font-medium truncate" title="${member.name || member.email}">
                                            ${member.name || 'No Name'}
                                        </div>
                                        <span class="ml-2 text-xs ${statusClass} px-2 py-1 rounded-full flex-shrink-0">
                                            <i class="fas ${statusIcon} mr-1"></i>${statusText}
                                        </span>
                                    </div>
                                    <div class="text-gray-500 text-xs truncate mt-1" title="${member.email}">
                                        ${member.email}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-400">
                                        ${member.email_verified_at ? 
                                            `<i class="fas fa-check-circle text-green-500 mr-1"></i>Email verified ${new Date(member.email_verified_at).toLocaleDateString()}` : 
                                            `<i class="fas fa-clock text-yellow-500 mr-1"></i>Invitation sent`
                                        }
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2 ml-3 flex-shrink-0">
                                <button type="button" 
                                        onclick="openDeleteMemberModal(${teamId}, ${member.id}, '${member.name || member.email}')"
                                        class="text-xs text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-full transition duration-150"
                                        title="Remove Member">
                                    <i class="fas fa-user-minus text-sm"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                membersList.innerHTML = html;
            } else {
                membersList.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                        <p class="text-sm text-gray-500">No members yet</p>
                        <p class="text-xs text-gray-400 mt-1">Add your first member using the form on the right</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading members:', error);
            membersList.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-300 text-4xl mb-3"></i>
                    <p class="text-sm text-red-500">Error loading members</p>
                    <p class="text-xs text-red-400 mt-1">${error.message}</p>
                    <button onclick="loadExistingMembers(${teamId})" class="mt-2 text-xs text-blue-600 hover:text-blue-800">
                        <i class="fas fa-redo mr-1"></i>Retry
                    </button>
                </div>
            `;
        });
    }

    function viewProfilePhoto(photoUrl, userName) {
        const modal = document.getElementById('photoViewerModal');
        const photo = document.getElementById('viewerPhoto');
        const photoName = document.getElementById('viewerPhotoName');
        
        photo.src = photoUrl;
        photo.alt = `${userName}'s Profile Photo`;
        photoName.textContent = `${userName}'s Profile Photo`;
        
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closePhotoViewer() {
        document.getElementById('photoViewerModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function sendInvitation() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const teamId = document.getElementById('teamId').value;
        
        if (!name.trim()) {
            showAlert('error', 'Please enter a name for the team member!');
            document.getElementById('name').focus();
            return;
        }
        
        if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            showAlert('error', 'Please enter a valid email address!');
            document.getElementById('email').focus();
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const submitBtn = document.querySelector('#addMemberForm button[type="button"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
        submitBtn.disabled = true;
        
        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('team_id', teamId);
        formData.append('_token', csrfToken);
        
        fetch('/admin/teams/add-member', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Add member response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Add member error response:', text);
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Add member success data:', data);
            if (data.success) {
                showAlert('success', data.message || 'Invitation sent successfully!');
                document.getElementById('addMemberForm').reset();
                loadExistingMembers(teamId);
                
                // Update member count
                setTimeout(() => {
                    const memberCountElement = document.getElementById('existingMembersList');
                    const memberItems = memberCountElement.querySelectorAll('.member-item');
                    const memberCount = memberItems.length;
                    const memberCountText = `${memberCount} member${memberCount !== 1 ? 's' : ''}`;
                    document.getElementById('teamMemberCount').innerHTML = `<i class="fas fa-users mr-1"></i> ${memberCountText}`;
                    document.getElementById('memberCountBadge').textContent = memberCountText;
                }, 500);
            } else {
                showAlert('error', data.message || 'Failed to send invitation.');
            }
        })
        .catch(error => {
            console.error('Error sending invitation:', error);
            showAlert('error', 'An error occurred: ' + error.message);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `custom-alert fixed top-4 right-4 px-4 py-3 rounded-md shadow-lg z-50 transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 
            'bg-red-100 border border-red-400 text-red-700'
        }`;
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Event listeners for modals
    document.getElementById('manageMembersModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeManageMembersModal();
        }
    });

    document.getElementById('deleteTeamModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteTeamModal();
        }
    });

    document.getElementById('deleteMemberModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteMemberModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('manageMembersModal').classList.contains('hidden')) {
                closeManageMembersModal();
            }
            if (!document.getElementById('photoViewerModal').classList.contains('hidden')) {
                closePhotoViewer();
            }
            if (!document.getElementById('deleteTeamModal').classList.contains('hidden')) {
                closeDeleteTeamModal();
            }
            if (!document.getElementById('deleteMemberModal').classList.contains('hidden')) {
                closeDeleteMemberModal();
            }
        }
    });
</script>

    <style>
        /* Custom scrollbar for better appearance */
        #existingMembersList::-webkit-scrollbar,
        .pr-2::-webkit-scrollbar {
            width: 6px;
        }
        
        #existingMembersList::-webkit-scrollbar-track,
        .pr-2::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        #existingMembersList::-webkit-scrollbar-thumb,
        .pr-2::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        #existingMembersList::-webkit-scrollbar-thumb:hover,
        .pr-2::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Ensure proper truncation */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Modal animation */
        #manageMembersModal > div,
        #deleteTeamModal > div,
        #deleteMemberModal > div {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Photo Viewer Modal Styles */
        #photoViewerModal {
            backdrop-filter: blur(8px);
        }
        
        #photoViewerModal > button {
            opacity: 0.8;
            transition: all 0.2s;
        }
        
        #photoViewerModal > button:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        #viewerPhoto {
            animation: photoZoomIn 0.3s ease-out;
        }
        
        @keyframes photoZoomIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Profile photo hover effect */
        .cursor-pointer:hover {
            transform: translateY(-1px);
            transition: transform 0.2s;
        }
        
        /* Status indicator pulse for active users */
        @keyframes status-pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }
            50% {
                box-shadow: 0 0 0 4px rgba(34, 197, 94, 0);
            }
        }
        
        .bg-green-500 {
            animation: status-pulse 2s infinite;
        }
        
        /* Z-index fixes */
        #deleteTeamModal, #deleteMemberModal {
            z-index: 100;
        }
        
        #manageMembersModal {
            z-index: 50;
        }
        
        #photoViewerModal {
            z-index: 200;
        }
    </style>
</x-app-layout>