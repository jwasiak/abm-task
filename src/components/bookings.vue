<template>

    <div>

        <h1 class="title">Lista rezerwacji</h1>

        <div class="buttons">
            <button class="button is-success" v-on:click="openModal()">Dodaj</button>
        </div>

        <table class="table is-fullwidth">

            <thead>
                <tr>
                    <th>Miejsce</th>
                    <th>Od</th>
                    <th>Do</th>
                    <th>Osoba</th>
                    <th>Akcja</th>
                </tr>
            </thead>

            <tbody>

                <tr v-for="record in collection">
                    <td>{{ record.mark }}</td>
                    <td>{{ formatTimeStamp(record.start) }}</td>
                    <td>{{ formatTimeStamp(record.stop) }}</td>
                    <td>{{ record.name }} {{ record.surname }}</td>
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
                        <p class="modal-card-title">Edycja rezerwacji</p>
                        <button type="button" class="delete" aria-label="close" v-on:click="closeModal()"></button>
                    </header>
                    <section class="modal-card-body">

                        <input class="input" type="hidden"  v-model="booking.id">


                            <div class="field">
                                <div class="control is-expanded">
                                    <div class="select is-fullwidth">
                                        <select v-model="booking.person_id" required>
                                            <option v-for="option in persons" v-bind:value="option.value">
                                                {{ option.text }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <p>Początek</p>


                            <div class="field is-grouped">
                                <div class="control is-expanded">
                                    <input class="input" type="date" v-model="booking.start" required>
                                </div>

                                 <div class="control is-expanded">
                                    <input class="input" type="time" v-model="booking.from" required>
                                </div>
                            </div>

                            <p>Koniec</p>


                            <div class="field is-grouped">
                                <div class="control is-expanded">
                                    <input class="input" type="date" v-model="booking.stop" required>
                                </div>

                                 <div class="control is-expanded">
                                    <input class="input" type="time" v-model="booking.to" required>
                                </div>
                            </div>


                            <div class="field">
                                <div class="control is-expanded">
                                    <div class="select is-fullwidth">
                                        <select v-model="booking.spot_id" v-on:change="spotTools(booking.spot_id)" required>
                                            <option v-for="option in spots" v-bind:value="option.value">
                                                {{ option.text }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <p v-if="tools.length">
                            Wyposażenie:
                            <ul>
                                <li v-for="record in tools"> {{ record.model }} {{ record.mark }} </li>
                            </ul>
                            </p>


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
                booking: {
                    id: null,
                    spot_id: '',
                    person_id: '',
                    start: null,
                    stop: null,
                    from: null,
                    to: null
                },
                modal: false,
                collection: [],
                persons: [],
                spots: [],
                tools: []

            }
        },
        created: function() {
            this.$emit("message", '');
            this.fetch();
            this.fetchPersons();
            this.fetchSpots();
        },
        methods: {
            formatTimeStamp: function(ts) {
                var date = ts.substring(0,10),
                    time = ts.substring(11,16);
                return date + ' godz. ' + time;
            },
            spotTools: function(id) {
                var self = this;
                var url = this.url + 'spot-tools/' + id;
                axios.get(url)
                .then(function (response) {
                    self.$emit("message", response.data.msg);
                    if (response.data.collection) {
                        self.tools = response.data.collection;
                    } else {
                        self.tools = [];
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            fetchSpots: function() {
                var url = this.url + 'spots-options';
                var self = this;
                axios.get(url)
                .then(function (response) {
                    if ( response.data.collection ) {
                        self.spots = response.data.collection;
                        self.spots.push( {value:'', text:'-- Wybierz miejsce --'} );
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            fetchPersons: function() {
                var url = this.url + 'persons-options';
                var self = this;
                axios.get(url)
                .then(function (response) {
                    if ( response.data.collection ) {
                        self.persons = response.data.collection;
                        self.persons.push( {value:'', text:'-- Wybierz osobę --'} );
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            editData: function(id) {
                var self = this;
                var url = this.url + 'booking/' + id;
                axios.get(url)
                .then(function (response) {
                    self.$emit("message", response.data.msg);
                    if (response.data.model) {
                        self.booking = response.data.model;
                        self.spotTools(self.booking.spot_id);
                        self.modal = true;
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            deleteData: function(id) {
                var self = this;
                var url = this.url + 'booking/' + id;
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
                var url = this.url + 'bookings';
                var self = this;
                axios.get(url)
                .then(function (response) {
                    if ( response.data.collection ) {
                        self.collection = response.data.collection;
                    } else {
                        self.$emit("message", response.data.msg);
                    }

                })
                .catch(function (error) {
                    alert(error);
                });
            },
            resetForm: function() {
                this.booking.id = null;
                this.booking.person_id = '';
                this.booking.spot_id = '';
                this.booking.start = null;
                this.booking.from = null;
                this.booking.stop = null;
                this.booking.to = null;
                this.tools = [];
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
                var url = window.location.pathname + 'booking';
                var self = this;
                axios.post(url, this.booking )
                .then(function (response) {
                    if ( response.data.msg ) {
                            alert(response.data.msg);
                    } else {
                        if ( response.data.model.id ) {
                            self.fetch();
                            self.resetForm();
                            self.modal = false;
                        }
                    }
                })
                .catch(function (error) {
                    alert(error);
                });
            },
            validateForm: function() {
                var valid = true;
                if ( !this.booking.person_id ) {
                    valid = false;
                    alert ('Wskaż osobę rezserwującą');
                }
                if ( !this.booking.spot_id ) {
                    valid = false;
                    alert ('Wybierz miejsce rezerwacji');
                }
                return valid;
            },
        }
    }

</script>
