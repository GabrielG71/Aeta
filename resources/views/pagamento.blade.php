@extends('layouts.master')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gerenciamento de Pagamentos</h2>
        <div class="space-x-4">
            <button onclick="openAddModal()" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-200">
                Adicionar Pagamento
            </button>
            <button onclick="openManageModal()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Gerenciar Pagamentos
            </button>
        </div>
    </div>

    <!-- Lista de Pagamentos Existentes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Pagamentos Cadastrados</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prazo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuários</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pagamentos as $pagamento)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pagamento->descricao }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($pagamento->prazo_pagamento)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                @foreach($pagamento->users as $user)
                                    <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs text-gray-700 mr-1 mb-1">{{ $user->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pagamento->isVencido())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Vencido</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum pagamento cadastrado</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Adicionar Pagamento -->
<div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Adicionar Novo Pagamento</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="addForm" class="space-y-4">
                @csrf
                
                <!-- Seleção de Usuários -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usuários</label>
                    <div class="mb-2">
                        <button type="button" onclick="toggleAllUsers()" class="text-sm text-purple-600 hover:text-purple-800">
                            Selecionar/Desselecionar Todos
                        </button>
                    </div>
                    <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-2">
                        @foreach($usuarios as $user)
                        <label class="flex items-center">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $user->name }} (CPF: {{ $user->cpf }})</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Descrição</label>
                    <input type="text" name="descricao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Valor (R$)</label>
                    <input type="number" name="valor" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Prazo de Pagamento</label>
                    <input type="date" name="prazo_pagamento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeAddModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition duration-200">
                        Criar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Gerenciar Pagamentos -->
<div id="manageModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Gerenciar Pagamentos</h3>
                <button onclick="closeManageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Prazo</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Usuários</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagamentos as $pagamento)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-sm">{{ $pagamento->descricao }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-sm">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($pagamento->prazo_pagamento)->format('d/m/Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-sm">
                                <div class="max-w-xs">
                                    @foreach($pagamento->users as $user)
                                        <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs text-gray-700 mr-1 mb-1">{{ $user->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm">
                                <div class="flex space-x-2">
                                    <button onclick="editPagamento({{ $pagamento->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600 transition duration-200">
                                        Editar
                                    </button>
                                    <button onclick="deletePagamento({{ $pagamento->id }})" class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition duration-200">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Pagamento -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Editar Pagamento</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="editForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editPagamentoId" name="pagamento_id">
                
                <!-- Seleção de Usuários -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usuários</label>
                    <div class="mb-2">
                        <button type="button" onclick="toggleAllUsersEdit()" class="text-sm text-purple-600 hover:text-purple-800">
                            Selecionar/Desselecionar Todos
                        </button>
                    </div>
                    <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-2" id="editUsersList">
                        @foreach($usuarios as $user)
                        <label class="flex items-center">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="edit-user-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $user->name }} (CPF: {{ $user->cpf }})</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Descrição</label>
                    <input type="text" name="descricao" id="editDescricao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Valor (R$)</label>
                    <input type="number" name="valor" id="editValor" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Prazo de Pagamento</label>
                    <input type="date" name="prazo_pagamento" id="editPrazo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition duration-200">
                        Atualizar Pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Dados dos pagamentos para JavaScript
const pagamentosData = @json($pagamentos);

// Funções para Modal Adicionar
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addForm').reset();
    uncheckAllUsers();
}

function toggleAllUsers() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}

function uncheckAllUsers() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Funções para Modal Gerenciar
function openManageModal() {
    document.getElementById('manageModal').classList.remove('hidden');
}

function closeManageModal() {
    document.getElementById('manageModal').classList.add('hidden');
}

// Funções para Modal Editar
function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editForm').reset();
}

function toggleAllUsersEdit() {
    const checkboxes = document.querySelectorAll('.edit-user-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}

// Função para editar pagamento
function editPagamento(id) {
    const pagamento = pagamentosData.find(p => p.id === id);
    if (!pagamento) return;
    
    // Preenche os campos do modal
    document.getElementById('editPagamentoId').value = id;
    document.getElementById('editDescricao').value = pagamento.descricao;
    document.getElementById('editValor').value = pagamento.valor;
    document.getElementById('editPrazo').value = pagamento.prazo_pagamento;
    
    // Marca os usuários associados
    const userIds = pagamento.users.map(u => u.id);
    document.querySelectorAll('.edit-user-checkbox').forEach(checkbox => {
        checkbox.checked = userIds.includes(parseInt(checkbox.value));
    });
    
    closeManageModal();
    openEditModal();
}

// Função para excluir pagamento
function deletePagamento(id) {
    if (!confirm('Tem certeza que deseja excluir este pagamento?')) return;
    
    fetch(`/pagamento/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao excluir pagamento', 'error');
    });
}

// Submit do formulário de adicionar
document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
    
    if (selectedUsers.length === 0) {
        showNotification('Selecione pelo menos um usuário', 'error');
        return;
    }
    
    fetch('/pagamento', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeAddModal();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao criar pagamento', 'error');
    });
});

// Submit do formulário de editar
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('editPagamentoId').value;
    const selectedUsers = document.querySelectorAll('.edit-user-checkbox:checked');
    
    if (selectedUsers.length === 0) {
        showNotification('Selecione pelo menos um usuário', 'error');
        return;
    }
    
    fetch(`/pagamento/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-HTTP-Method-Override': 'PUT'
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeEditModal();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao atualizar pagamento', 'error');
    });
});

// Função para mostrar notificações
function showNotification(message, type) {
    // Remove notificação existente se houver
    const existingNotification = document.getElementById('notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.id = 'notification';
    notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove a notificação após 5 segundos
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Fechar modals ao clicar fora
window.onclick = function(event) {
    const addModal = document.getElementById('addModal');
    const manageModal = document.getElementById('manageModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === addModal) {
        closeAddModal();
    }
    if (event.target === manageModal) {
        closeManageModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
}
</script>

@endsection