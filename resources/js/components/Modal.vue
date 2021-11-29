<template>
    <div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ titulo }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <slot name="alerta"></slot>
                <slot name="conteudo"></slot>
            </div>
            <div class="modal-footer">
                <slot name="rodape"></slot>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'id', 
            'titulo'
            ]
    }
</script>