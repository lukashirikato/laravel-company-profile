@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 to-black py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- HEADER -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <i class="ri-qr-code-2-line mr-3 text-primary"></i>Member QR Code Management
                </h1>
                <p class="text-gray-400">Manage, monitor, and troubleshoot member QR codes</p>
            </div>
            <a href="{{ route('admin.dashboard') ?? '#' }}" class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                <i class="ri-arrow-left-line"></i> Back to Admin
            </a>
        </div>

        <!-- STATS CARDS -->
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-green-900/20 to-green-800/20 border border-green-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-400 mb-1">QR Codes Generated</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['total_qr_codes'] ?? 0 }}</p>
                    </div>
                    <i class="ri-qr-code-line text-4xl text-green-600 opacity-50"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-900/20 to-blue-800/20 border border-blue-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-400 mb-1">Active QR Codes</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['active_qr_codes'] ?? 0 }}</p>
                    </div>
                    <i class="ri-check-double-line text-4xl text-blue-600 opacity-50"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-900/20 to-purple-800/20 border border-purple-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-400 mb-1">Inactive QR Codes</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['inactive_qr_codes'] ?? 0 }}</p>
                    </div>
                    <i class="ri-close-circle-line text-4xl text-purple-600 opacity-50"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-900/20 to-yellow-800/20 border border-yellow-700 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-400 mb-1">No QR Code</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['no_qr_code'] ?? 0 }}</p>
                    </div>
                    <i class="ri-alert-line text-4xl text-yellow-600 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- SEARCH & FILTER -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-8">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Search by Name or Email</label>
                    <input type="text" id="search-input" placeholder="Member name or email..." 
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Filter by Status</label>
                    <select id="filter-status" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="none">No QR Code</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="applyFilter()" class="w-full bg-primary hover:bg-secondary text-white font-semibold py-2 rounded-lg transition">
                        <i class="ri-search-line mr-2"></i>Search
                    </button>
                </div>
            </div>
        </div>

        <!-- MEMBERS LIST -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="ri-list-check-2 text-primary"></i> Member QR Code Status
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-700/50 border-b border-gray-700">
                            <th class="text-left px-6 py-4 text-gray-300 font-semibold text-sm">Member ID</th>
                            <th class="text-left px-6 py-4 text-gray-300 font-semibold text-sm">Name</th>
                            <th class="text-left px-6 py-4 text-gray-300 font-semibold text-sm">Email</th>
                            <th class="text-left px-6 py-4 text-gray-300 font-semibold text-sm">QR Status</th>
                            <th class="text-left px-6 py-4 text-gray-300 font-semibold text-sm">Generated</th>
                            <th class="text-left px-6 py-4 text-gray-300 font-semibold text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="members-table-body">
                        <!-- Will be populated by API/JavaScript -->
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="ri-loader-4-line text-2xl animate-spin"></i>
                                <p class="mt-2">Loading members...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BULK ACTIONS -->
        <div class="mt-8 bg-gray-800 rounded-xl p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <i class="ri-settings-line text-primary"></i> Bulk Actions
            </h3>
            <div class="flex flex-col md:flex-row gap-3">
                <button onclick="generateQRForAll()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="ri-qr-code-line"></i> Generate Missing QR Codes
                </button>
                <button onclick="enableAllQR()" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="ri-check-line"></i> Enable All QR Codes
                </button>
                <button onclick="disableAllQR()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="ri-close-line"></i> Disable All QR Codes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FOR MEMBER DETAILS -->
<div id="member-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-gray-800 rounded-xl shadow-lg max-w-md w-full p-6 relative border border-gray-700">
        <button onclick="closeMemberModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-300 text-2xl">×</button>
        
        <h2 id="modal-title" class="text-2xl font-bold text-white mb-4">Member QR Code</h2>
        
        <div id="modal-content" class="space-y-4">
            <!-- QR Code Display -->
            <div class="bg-white rounded-lg p-4 flex justify-center">
                <div id="modal-qr" class="w-64 h-64 flex items-center justify-center">
                    <i class="ri-qr-code-line text-6xl text-gray-300"></i>
                </div>
            </div>

            <!-- Member Details -->
            <div>
                <p class="text-sm text-gray-400 mb-1">Member Info</p>
                <div class="bg-gray-700 rounded-lg p-3 space-y-1">
                    <p class="text-white font-semibold" id="modal-name"></p>
                    <p class="text-gray-300 text-sm" id="modal-email"></p>
                    <p class="text-gray-400 text-sm">Member ID: <span id="modal-id" class="font-mono"></span></p>
                </div>
            </div>

            <!-- QR Status -->
            <div>
                <p class="text-sm text-gray-400 mb-1">QR Code Status</p>
                <div class="bg-gray-700 rounded-lg p-3">
                    <p class="text-white font-semibold" id="modal-status"></p>
                    <p class="text-gray-400 text-sm mt-1">Generated: <span id="modal-generated"></span></p>
                </div>
            </div>

            <!-- QR Token -->
            <div>
                <p class="text-sm text-gray-400 mb-1">QR Token</p>
                <div class="bg-gray-700 rounded-lg p-3">
                    <p class="text-gray-300 font-mono text-xs break-all" id="modal-token"></p>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex gap-2">
                <button onclick="downloadQRFromModal()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                    <i class="ri-download-line mr-1"></i>Download
                </button>
                <button onclick="regenerateQRFromModal()" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                    <i class="ri-refresh-line mr-1"></i>Regenerate
                </button>
                <button onclick="toggleQRFromModal()" id="modal-toggle-btn" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                    <i class="ri-close-line mr-1"></i>Disable
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const token = '{{ csrf_token() }}';
    let currentMemberId = null;
    let members = [];

    // Load members on page load
    document.addEventListener('DOMContentLoaded', loadMembers);

    async function loadMembers() {
        try {
            // For demo, we'll use a simple approach with PHP
            // In production, create an API endpoint to fetch all members with QR info
            const response = await fetch('{{ route("admin.api.members.qr") ?? "#" }}', {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                members = await response.json();
                renderMembersTable(members);
            }
        } catch (error) {
            console.error('Failed to load members:', error);
            document.getElementById('members-table-body').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <p>Unable to load members data. Please refresh the page.</p>
                    </td>
                </tr>
            `;
        }
    }

    function renderMembersTable(data) {
        const tbody = document.getElementById('members-table-body');
        
        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="ri-user-line text-4xl mb-2 opacity-50"></i>
                        <p>No members found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = data.map(member => {
            let statusIcon = '⚠️';
            let statusText = 'No QR Code';
            let statusBg = 'bg-yellow-900/50 text-yellow-400';

            if (member.qr_token) {
                if (member.qr_active) {
                    statusIcon = '✓';
                    statusText = 'Active';
                    statusBg = 'bg-green-900/50 text-green-400';
                } else {
                    statusIcon = '✗';
                    statusText = 'Inactive';
                    statusBg = 'bg-red-900/50 text-red-400';
                }
            }

            return `
                <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition">
                    <td class="px-6 py-4">
                        <span class="text-white font-mono">${String(member.id).padStart(4, '0')}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-white font-semibold">${member.name}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400 text-sm">${member.email}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 ${statusBg} rounded-full text-sm font-semibold">
                            ${statusIcon} ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400 text-sm">${member.qr_generated_at ? new Date(member.qr_generated_at).toLocaleDateString() : '-'}</span>
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="openMemberModal(${member.id})" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                            <i class="ri-eye-line mr-1"></i>View
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function applyFilter() {
        const search = document.getElementById('search-input').value.toLowerCase();
        const status = document.getElementById('filter-status').value;

        const filtered = members.filter(member => {
            const matchSearch = member.name.toLowerCase().includes(search) || 
                              member.email.toLowerCase().includes(search);
            
            let matchStatus = true;
            if (status === 'active') matchStatus = member.qr_token && member.qr_active;
            if (status === 'inactive') matchStatus = member.qr_token && !member.qr_active;
            if (status === 'none') matchStatus = !member.qr_token;

            return matchSearch && matchStatus;
        });

        renderMembersTable(filtered);
    }

    function openMemberModal(memberId) {
        const member = members.find(m => m.id === memberId);
        if (!member) return;

        currentMemberId = memberId;

        document.getElementById('modal-name').textContent = member.name;
        document.getElementById('modal-email').textContent = member.email;
        document.getElementById('modal-id').textContent = String(member.id).padStart(4, '0');
        document.getElementById('modal-generated').textContent = member.qr_generated_at 
            ? new Date(member.qr_generated_at).toLocaleDateString() 
            : 'Not generated';

        if (member.qr_token) {
            const button = document.getElementById('modal-toggle-btn');
            if (member.qr_active) {
                document.getElementById('modal-status').textContent = '✓ Active';
                button.textContent = '✗ Disable QR';
                button.className = 'flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition text-sm';
            } else {
                document.getElementById('modal-status').textContent = '✗ Inactive';
                button.textContent = '✓ Enable QR';
                button.className = 'flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition text-sm';
            }

            document.getElementById('modal-token').textContent = member.qr_token;

            // Fetch and display QR code
            fetchQRImage(memberId);
        } else {
            document.getElementById('modal-status').textContent = '⚠️ No QR Code';
            document.getElementById('modal-token').textContent = 'Not generated yet';
            document.getElementById('modal-toggle-btn').textContent = 'Generate QR Code';
        }

        document.getElementById('member-modal').classList.remove('hidden');
        document.getElementById('member-modal').classList.add('flex');
    }

    function closeMemberModal() {
        document.getElementById('member-modal').classList.add('hidden');
        document.getElementById('member-modal').classList.remove('flex');
        currentMemberId = null;
    }

    function fetchQRImage(memberId) {
        // Fetch and display QR code
        document.getElementById('modal-qr').innerHTML = '<i class="ri-loader-4-line text-4xl animate-spin text-primary"></i>';
        // In a real implementation, fetch the QR image from an API
    }

    function downloadQRFromModal() {
        alert('Download functionality coming soon');
    }

    function regenerateQRFromModal() {
        if (!currentMemberId) return;
        if (!confirm('Regenerate QR code? Old QR code will become invalid.')) return;

        alert('Regenerate functionality coming soon');
    }

    function toggleQRFromModal() {
        if (!currentMemberId) return;
        alert('Toggle QR status functionality coming soon');
    }

    function generateQRForAll() {
        if (!confirm('Generate QR codes for all members without QR codes?')) return;
        alert('Bulk generate functionality coming soon');
    }

    function enableAllQR() {
        if (!confirm('Enable all QR codes?')) return;
        alert('Bulk enable functionality coming soon');
    }

    function disableAllQR() {
        if (!confirm('Disable all QR codes?')) return;
        alert('Bulk disable functionality coming soon');
    }
</script>
@endsection
