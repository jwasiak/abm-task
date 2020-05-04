<template>
    <div>

    <section class="hero">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">
        ABM Group
      </h1>
      <h2 class="subtitle">
        System rezerwacji miejsc
      </h2>
    </div>
  </div>
</section>

    <div class="columns">
        <app-menu v-on:module="selectModule"></app-menu>


        <div class="container column" >
            <div class="notification is-info" v-if="message">
                <button class="delete" v-on:click="closeMessage()"></button>
                {{ message }}
            </div>

            <persons-module v-if="module === 'persons'" v-on:message="setMessage"></persons-module>
            <spots-module v-if="module === 'spots'"  v-on:message="setMessage"></spots-module>
            <tools-module v-if="module === 'tools'"  v-on:message="setMessage"></tools-module>
            <bookings-module v-if="module === 'bookings'" v-on:message="setMessage"></bookings-module>

        </div>
    </div>

</div>

</template>


<script>

import AppMenu from './components/menu.vue';
import Persons from './components/persons.vue';
import Spots from './components/spots.vue';
import Tools from './components/tools.vue';
import Bookings from './components/bookings.vue';

export default {
    data () {
        return {
            message: '',
            module: 'bookings'
        }
    },
    methods: {
        selectModule: function(module) {
            this.module = module
        },
        closeMessage: function() {
            this.message = ''
        },
        setMessage: function(msg) {
            this.message = msg
        }
    },
    components: {
        "app-menu": AppMenu,
        "persons-module": Persons,
        "spots-module": Spots,
        "tools-module": Tools,
        "bookings-module": Bookings
    }
}
</script>
