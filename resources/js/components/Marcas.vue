<template>
    <div class="container">
        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:alerta>
                <alert-component class_status="success" msg_status="Sucesso!" msg="Marca cadastrada com sucesso!"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group mb-3">
                    <label for="novo-nome">Nome da Marca</label>
                    <input type="text" class="form-control" id="novo-nome" placeholder="Informe o nome da nova Marca" v-model="nome_marca" required>
                </div>
                <div class="form-group">
                    <label for="imagem-marca">Imagem da Marca</label>
                    <input type="file" class="form-control-file" accept="image/jpeg, image/jpg, image/png" id="imagem-marca" @change="carregarImagem($event)" required>
                    <small id="imagem-marca" class="form-text text-muted">Selecione a imagem da marca.</small>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times-circle"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" @click="salvar()">
                    <i class="fa fa-save"></i> Salvar
                </button>
            </template>
        </modal-component>
        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <!-- Início do card de busca -->
                <card-component titulo="Bem vindo!">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col form-group">
                                <label for="id">ID da Marca</label>
                                <input type="number" class="form-control" id="id" placeholder="Informe o ID da marca">
                            </div>
                            <div class="col form-group">
                                <label for="nome">Nome da Marca</label>
                                <input type="text" class="form-control" id="nome" placeholder="Informe o nome da Marca">
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fa fa-search mr-1"></i> Pesquisar
                        </button>
                    </template>
                </card-component>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Início do card de listagem -->
                <card-component titulo="Relação de Marcas">
                    <template v-slot:btn-header>
                        <button type="button" class="btn btn-success float-right btn-sm" data-toggle="modal" data-target="#modalMarca">
                            <i class="fa fa-plus-circle"></i> Adicionar Marca
                        </button>
                    </template>
                    <template v-slot:conteudo>
                        <table-component></table-component>
                    </template>
                </card-component>
            </div>
        </div>
    </div>
</template>

<script>
import Alert from './Alert.vue'
    export default {
  components: { Alert },
        data() {
            return {
                nome_marca: '',
                arquivos_imagens: [],
                url_base: 'http://localhost:8000/api/v1/marca',
            }
        },
        methods: {
            carregarImagem(e) {
                this.arquivos_imagens = e.target.files
            },
            salvar() {
                let form_data = new FormData()
                form_data.append('nome', this.nome_marca)
                form_data.append('imagem', this.arquivos_imagens[0])

                let config = {
                    headers: {
                        'Content-Type': 'multpart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.url_base, form_data, config)
                    .then(response => {
                        console.log(response)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            }
        },
        computed: {
            token() {
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=')
                }).replace('token=', 'Bearer ')

                return token
            }   
        }
    }
</script>
