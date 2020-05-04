<template>

    <div>

        <h1 class="title">Lista wyposażenia</h1>

        <div class="buttons">
            <button class="button is-success" v-on:click="openModal()">Dodaj</button>
        </div>

        <table class="table is-fullwidth">

            <thead>
                <tr>
                    <th>Rodzaj</th>
                    <th>Model</th>
                    <th>Oznaczenie</th>
                    <th>Akcja</th>
                </tr>
            </thead>

            <tbody>

                <tr v-for="record in collection">
                    <td>{{ record.kind }}</td>
                    <td>{{ record.model }}</td>
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
                        <p class="modal-card-title">Edycja danych wyposażenia</p>
                        <button type="button" class="delete" aria-label="close" v-on:click="closeModal()"></button>
                    </header>
                    <section class="modal-card-body">

                        <input class="input" type="hidden"  v-model="tool.id">

                        <div class="field">
                            <div class="control">
                             <div class="select">
                              <select v-model="tool.kind">
                                <option> -- Wybierz -- </option>
                                <option>komputer</option>
                                <option>oświetlenie</option>
                                <option>meble</option>
                                <option>telekomunikacja</option>
                                </select>
                            </div>
                    </div>
                </div>


                <div class="field">
                    <div class="control">
                        <input class="input" type="text" placeholder="Model" v-model="tool.model" >
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <input class="input" type="text" placeholder="Oznaczenie" v-model="tool.mark" required>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <input class="input" type="number" placeholder="Rok zakupu" v-model="tool.year">
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <input class="input" type="value" placeholder="Wartość" v-model="tool.value" pattern="\d+(\.\d{2})?">
                    </div>
                </div>

                <textarea class="textarea" placeholder="Opis" v-model="tool.descr"></textarea>

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
                tool: {
                    id: null,
                    kind: null,
                    model: null,
                    mark: null,
                    year: null,
                    value: null,
                    descr: null
                },
                modal: false,
                collection: [],

            }
        },
        created: function() {
            this.$emit("message", '');
            this.fetch();
        },
        methods: {
            editData: function(id) {
                var self = this;
                var url = this.url + 'tool/' + id;
                axios.get(url)
                .then(function (response) {
                    self.$emit("message", response.data.msg);
                    if (response.data.model) {
                        self.tool = response.data.model;
                        self.modal = true;

                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            deleteData: function(id) {
                var self = this;
                var url = this.url + 'tool/' + id;
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
                var url = this.url + 'tools';
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
            resetForm: function() {
                this.tool.id = null;
                this.tool.kind = null;
                this.tool.model = null;
                this.tool.mark = null;
                this.tool.year = null;
                this.tool.value = null;
                this.tool.descr = null;
            },
            openModal: function() {
                this.modal = true;
            },
            closeModal: function() {
                this.modal = false;
            },
            saveData: function() {
                if ( !this.validateForm() ) {
                    return;
                }
                var url = window.location.pathname + 'tool';
                var self = this;
                axios.post(url, this.tool )
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
                if ( !this.tool.mark ) {
                    valid = false;
                    alert ('Podaj numer lub nazwę wyposażenia');
                }
                return valid;
            },
        }
    }

</script>
