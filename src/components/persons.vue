<template>

    <div>

        <h1 class="title">Lista osób</h1>

        <div class="buttons">
            <button class="button is-success" v-on:click="openModal()">Dodaj</button>
        </div>

        <table class="table is-fullwidth">

            <thead>
                <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Telefon</th>
                    <th>Email</th>
                    <th>Akcja</th>
                </tr>
            </thead>

            <tbody>

                <tr v-for="record in collection">
                    <td>{{ record.name }}</td>
                    <td>{{ record.surname }}</td>
                    <td>{{ record.phone }}</td>
                    <td>{{ record.email }}</td>
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
                        <p class="modal-card-title">Edycja danych osoby</p>
                        <button type="button" class="delete" aria-label="close" v-on:click="closeModal()"></button>
                    </header>
                    <section class="modal-card-body">

                        <input class="input" type="hidden"  v-model="person.id">

                        <div class="field">
                            <div class="control">
                                <input class="input" type="text" placeholder="Imię" v-model="person.name" ref="name">
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input class="input" type="text" placeholder="Nazwisko" v-model="person.surname" required autocapitalize="words">
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input class="input" type="text" placeholder="Telefon" v-model="person.phone">
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input class="input" type="email" placeholder="Email" v-model="person.email">
                            </div>
                        </div>

                        <textarea class="textarea" placeholder="Opis" v-model="person.notes"></textarea>

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
                person: {
                    id: null,
                    name: null,
                    surname: null,
                    phone: null,
                    email: null,
                    notes: null
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
                var url = this.url + 'person/' + id;
                axios.get(url)
                .then(function (response) {
                    self.$emit("message", response.data.msg);
                    if (response.data.model) {
                        self.person = response.data.model;
                        self.modal = true;
                        self.$refs.name.focus();
                        console.log(self.$refs.name);
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            deleteData: function(id) {
                var self = this;
                var url = this.url + 'person/' + id;
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
                var url = this.url + 'persons';
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
                this.person.id = null;
                this.person.name = null;
                this.person.surname = null;
                this.person.phone = null;
                this.person.email = null;
                this.person.notes = null;
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
                var url = window.location.pathname + 'person';
                var self = this;
                axios.post(url, this.person )
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
                var mailRegEx =  /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if ( !this.person.surname ) {
                    valid = false;
                    alert ('Podaj nazwisko');
                }
                if ( this.person.email ) {
                    if ( !mailRegEx.test(this.person.email) ) {
                        valid = false;
                        alert ('Błędny adres e-mail');
                    }
                }
                return valid;
            },
        }
    }

</script>
