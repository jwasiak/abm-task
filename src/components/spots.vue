<template>

    <div>

        <h1 class="title">Lista miejsc pracy</h1>

        <div class="buttons">
            <button class="button is-success" v-on:click="openModal()">Dodaj</button>
        </div>

        <table class="table is-fullwidth">

            <thead>
                <tr>
                    <th>Oznaczenie (numer lub nazwa własna)</th>
                    <th>Akcja</th>
                </tr>
            </thead>

            <tbody>

                <tr v-for="record in collection">
                    <td>{{ record.mark }}</td>
                    <td>
                        <button class="button is-success" v-on:click="editData(record.id)"> Edytuj </button>
                        <button class="button is-danger" v-on:click="deleteData(record.id)"> Usuń </button>
                    </td>
                </tr>

            </tbody>

        </table>


        <div class="modal" v-bind:class="{ 'is-active': modal }" >
            <div class="modal-background"></div>
            <div class="modal-card">

                <form v-on:submit.prevent="saveData()">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Edycja miejsca</p>
                        <button type="button" class="delete" aria-label="close" v-on:click="closeModal()"></button>
                    </header>
                    <section class="modal-card-body">

                        <input class="input" type="hidden"  v-model="spot.id">

                        <div class="field">
                            <div class="control">
                                <input class="input" type="text" placeholder="Oznaczenie" v-model="spot.mark" required>
                            </div>
                        </div>

                        <textarea class="textarea" placeholder="Opis" v-model="spot.descr"></textarea>


                    <table class="table is-fullwidth" v-if="spot.id">
                        <tr>
                            <th colspan="2">Wyposażenie</th>
                        </tr>
                        <tr v-for="record in spot.tools">
                            <td> {{ record.model }} {{ record.mark }}</td>
                            <td style="text-align: right;"> <button type="button" class="button is-small" v-on:click="detachTool(record.id)"> Usuń </button> </td>
                        </tr>
                    </table>

                    <div v-if="spot.id">

                        <div class="select" v-if="tools.length">

                        <select v-model="toolId" v-on:change="linkTool()">
                            <option v-for="option in tools" v-bind:value="option.value">
                                {{ option.text }}
                            </option>
                        </select>

                    </div>

                    <!-- <button type="button" class="button" v-on:click="linkTool()"> Dodaj </button> -->

                </div>

                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" class="button is-success">Zapisz</button>
                        <button type="button" class="button" v-on:click="resetForm()">Reset</button>
                    </footer>
                </form>


            </div>

        </div>

    </div>


</template>


<script>

    import axios from 'axios';

    export default {
        data: function() {
            return {
                url: window.location.pathname,
                spot: {
                    id: null,
                    mark: null,
                    descr: null,
                    tools: []
                },
                toolId: '',
                modal: false,
                collection: [],
                tools: []

            }
        },
        created: function() {
            this.$emit("message", '');
            this.fetch();
            // this.fetchTools();
        },
        methods: {
            detachTool: function(id) {
                var url = this.url + 'detach-tool/' + id  ;
                var self = this;
                axios.delete(url)
                .then(function (response) {
                    self.editData(self.spot.id);
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            linkTool: function() {
                var url = this.url + 'spot/' + this.spot.id + '/tool/' + this.toolId ;
                var self = this;
                axios.put(url)
                .then(function (response) {
                    self.editData(self.spot.id);
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            editData: function(id) {
                this.fetchTools();
                var self = this;
                var url = this.url + 'spot/' + id;
                axios.get(url)
                .then(function (response) {
                    self.$emit("message", response.data.msg);
                    if (response.data.model) {
                        self.spot = response.data.model;
                        self.modal = true;
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            deleteData: function(id) {
                var self = this;
                var url = this.url + 'spot/' + id;
                axios.delete(url)
                .then(function (response) {
                    self.$emit("message", response.data.msg);
                    self.fetch();
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            fetch: function() {
                var url = this.url + 'spots';
                var self = this;
                axios.get(url)
                .then(function (response) {
                    if ( response.data.collection ) {
                        self.collection = response.data.collection;
                    } else {
                        self.collection = [];
                        self.$emit("message", response.data.msg);
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            fetchTools: function() {
                var url = this.url + 'available-tools';
                var self = this;
                axios.get(url)
                .then(function (response) {
                    self.toolId = '';
                    if ( response.data.collection ) {
                        self.tools = response.data.collection;
                        self.tools.push( {value:'', text:'-- Wybierz wyposażenie --'} );
                    } else {
                        self.tools = [];
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            resetForm: function() {
                this.spot.id = null;
                this.spot.mark = null;
                this.spot.descr = null;
                this.spot.tools = [];
            },
            openModal: function() {
                this.resetForm();
                this.modal = true;
            },
            closeModal: function() {
                this.modal = false;
            },
            saveData: function() {
                if ( !this.validateForm() ) {
                    return;
                }
                var url = window.location.pathname + 'spot';
                var self = this;
                axios.post(url, this.spot )
                .then(function (response) {
                    if ( response.data.model.id ) {
                        self.fetch();
                        self.$emit("message", response.data.msg);
                        self.resetForm();
                        self.modal = false;
                    } else {
                        alert('Wystąpił problem');
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            validateForm: function() {
                var valid = true;
                if ( !this.spot.mark ) {
                    valid = false;
                    alert ('Podaj opis miejsca pracy');
                }
                return valid;
            },
        }
    }

</script>
