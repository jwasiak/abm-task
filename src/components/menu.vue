<template>
    <aside class="menu column is-one-fifth">
        <ul class="menu-list">
            <li v-on:click="run('bookings')" ><a>Rezerwacje</a></li>
            <li v-on:click="run('persons')" ><a>Osoby</a></li>
            <li v-on:click="run('spots')" ><a>Miejsca</a></li>
            <li v-on:click="run('tools')" ><a>Wyposażenie</a></li>
        </ul>
    </aside>
</template>

<script>

export default {
    methods: {
        run: function(module) {
            this.$emit("module", module);
        }
    }
}

</script>
