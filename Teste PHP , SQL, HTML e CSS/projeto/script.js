document.addEventListener("DOMContentLoaded", async () => {
    const selectEmpresa = document.getElementById("empresa");

    try {
        const response = await fetch("obter_empresas.php");
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const data = await response.json();
        data.dados.forEach(empresa => {
            const option = new Option(empresa.nome_empresa, empresa.id_empresa);
            selectEmpresa.appendChild(option);
        });
    } catch (error) {
        console.error(`Erro ao obter empresas: ${error.message}`);
    }
});

// Função para buscar e exibir contas a pagar
    async function exibirContas() {
        try {
            const response = await fetch('obter_empresas.php');
            if (!response.ok) {
                throw new Error(`Erro na requisição: ${response.statusText}`);
            }

            const data = await response.json();
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            const statusMap = {
                1: 'Pago',
                2: 'Pendente'
            };

            data.dados.forEach(conta => {
                if (conta.valor && conta.data_pagar !== null && conta.pago !== null) {
                    const valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(conta.valor);
                    const status = statusMap[conta.pago];
                    const linha = `
                    <tr>
                        <td>${valorFormatado}</td>
                        <td>${conta.data_pagar}</td>
                        <td>${status}</td>
                        <td class="actions">
                            <button class="inserirEditar" data-id="${conta.id_conta_pagar}">Inserir/Editar</button>
                            <button class="excluir" data-id="${conta.id_conta_pagar}">Excluir</button>
                            <button class="marcarPago" data-id="${conta.id_conta_pagar}" data-status="${conta.pago === 1 ? '1' : '2'}">${conta.pago === 1 ? 'Marcar como Pendente' : 'Marcar como Pago'}</button>
                        </td>
                    </tr>
                `;            
                    tbody.innerHTML += linha;
                }
            });

            const botoesMarcarPago = document.querySelectorAll('.marcarPago');
            botoesMarcarPago.forEach(botao => {
                botao.addEventListener('click', async function() {
                    const idContaPagar = this.getAttribute('data-id');
                    let novoStatus = 1; // Assume que queremos marcar como "pago"
            
                    // Alterna o status se o status atual for 1 (pago)
                    if (this.getAttribute('data-status') === '1') {
                        novoStatus = 2; // Marca como "pendente"
                    }
            
                    try {
                        const response = await fetch(`marcar_como_pago.php?id=${idContaPagar}&status=${novoStatus}`);
                        if (!response.ok) {
                            throw new Error(`Erro na requisição: ${response.statusText}`);
                        }
            
                        const responseData = await response.json();
                        console.log(responseData);
            
                        // Atualiza a tabela após a operação no servidor
                        await exibirContas();
                    } catch (error) {
                        console.error('Erro:', error);
                    }
                });
            });
            
        
        const botoesInserirEditar = document.querySelectorAll('.inserirEditar');
        botoesInserirEditar.forEach(botao => {
            botao.addEventListener('click', async function() {
                const idContaPagar = this.getAttribute('data-id');
                const novoValor = prompt('Digite o novo valor:');
    
                if (novoValor !== null && novoValor !== '') {
                    try {
                        // Enviar novoValor para o servidor usando uma requisição AJAX
                        const response = await fetch(`editar_valor_conta.php?id=${idContaPagar}&novoValor=${novoValor}`, {
                            method: 'PUT', // Método HTTP PUT para atualização
                        });
    
                        if (!response.ok) {
                            throw new Error(`Erro na requisição: ${response.statusText}`);
                        }
    
                        const responseData = await response.json();
                        console.log(responseData);
    
                        // Atualizar a tabela após a operação no servidor
                        await exibirContas();
                    } catch (error) {
                        console.error('Erro:', error);
                    }
                } else {
                    alert('Por favor, insira um valor válido.');
                }
            });
        });
    

        const botoesExcluir = document.querySelectorAll('.excluir');
        botoesExcluir.forEach(botao => {
            botao.addEventListener('click', async function() {
                const idContaPagar = this.getAttribute('data-id');

                // Confirmação antes de excluir
                const confirmarExclusao = confirm('Tem certeza de que deseja excluir esta conta?');

                if (confirmarExclusao) {
                    try {
                        const response = await fetch(`excluir.php?id=${idContaPagar}`);
                        if (!response.ok) {
                            throw new Error(`Erro na requisição: ${response.statusText}`);
                        }

                        const responseData = await response.json();
                        console.log(responseData);

                        // Atualiza a tabela após a operação no servidor
                        await exibirContas();
                    } catch (error) {
                        console.error('Erro:', error);
                    }
                }
            });
        });

    } catch (error) {
        console.error('Erro ao obter empresas:', error);
    }
}

// Chama a função para exibir contas ao carregar a página
exibirContas();




// Obtém o ID da conta a partir dos parâmetros da URL
const urlParams = new URLSearchParams(window.location.search);
const idContaPagar = urlParams.get('id');

// Função para preencher os campos do formulário com os dados da conta
async function preencherCamposConta(idContaPagar) {
    try {
        // Obter dados da conta do servidor usando o ID fornecido
        const response = await fetch(`obter_conta_por_id.php?id=${idContaPagar}`);
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const conta = await response.json();

        // Preencher os campos do formulário com os dados da conta
        document.getElementById('empresa').value = conta.empresa_id;
        document.getElementById('data').value = conta.data_pagar;
        document.getElementById('valor').value = conta.valor;
    } catch (error) {
        console.error('Erro:', error);
    }
}


// Verificar se há um ID de conta na URL e preencher os campos se existir
if (idContaPagar) {
    preencherCamposConta(idContaPagar);
}

const btnFiltrar = document.getElementById("btnFiltrar");

btnFiltrar.addEventListener("click", async () => {
    const filtroEmpresa = document.getElementById("filtroEmpresa").value;

    try {
        const response = await fetch(`filtrar_contas.php?empresa=${filtroEmpresa}`);
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const data = await response.json();
        // Atualizar a tabela com os resultados do filtro
        atualizarTabela(data);
    } catch (error) {
        console.error('Erro:', error);
    }
});

function atualizarTabela(data) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';

    data.dados.forEach(conta => {
        const valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(conta.valor);
        const status = conta.pago === 1 ? 'Pago' : 'Pendente';
        const linha = `
            <tr>
                <td>${valorFormatado}</td>
                <td>${conta.data_pagar}</td>
                <td>${status}</td>
                <td class="actions">
                    <button class="inserirEditar" data-id="${conta.id_conta_pagar}">Inserir</button>
                    <button class="excluir" data-id="${conta.id_conta_pagar}">Excluir</button>
                    <button class="marcarPago" data-id="${conta.id_conta_pagar}" data-status="${conta.pago === 1 ? '1' : '2'}">${conta.pago === 1 ? 'Marcar como Pendente' : 'Marcar como Pago'}</button>
                </td>
            </tr>
        `;
        tbody.innerHTML += linha;
    });
}
