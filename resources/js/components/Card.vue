<template>
    <div class="card">
    <div class="card-header">{{titulo}}
        <slot name="btn-header"></slot>
    </div>
    <div class="card-body">
        <slot name="conteudo"></slot>
    </div>
    <div class="card-footer">
        <slot name="rodape"></slot>
    </div>
</div>
</template>

<script>
    export default {
        props: ['titulo']
    }
</script>
