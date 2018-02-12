new Vue({
                el: '#app',
                data: {
                    filter: {
                        name: '',
                        destination_folder: '',
                        boolean_operator: 'allof',
                        email: ''
                    },
                    expressions: [
                        {
                            field_name: '',
                            operator: 'contains',
                            expr_value: ''
                        },
                        {
                            field_name: '',
                            operator: 'contains',
                            expr_value: ''
                        },
                        {
                            field_name: '',
                            operator: 'contains',
                            expr_value: ''
                        },
                        {
                            field_name: '',
                            operator: 'contains',
                            expr_value: ''
                        }
                    ],
                    code: ''
                },
                methods: {
                    saveFilter () {
                        var data = {
                            params: {
                                filter: this.filter,
                                expressions: this.expressions
                            }
                        }
                        axios.post('/api/saveFilter', data).then(function (response) {
                            this.code = response.data
                        }.bind(this))
                    },
                    getFilter () {
                        var data = {
                            params: {
                                email: this.filter.email
                            }
                        }
                        axios.get('/api/getFilter', data).then(function (response) {
                            if(response.data == '404'){
                                this.code = "File not found"
                            } else {
                                this.filter = response.data['filter']
                                this.expressions = response.data['expressions']
                            }
                        }.bind(this))
                    },
                    validate () {
                        var data = {
                            params: {
                                filter: this.filter,
                                expressions: this.expressions
                            }
                        }
                        axios.post('/api/validate', data).then(function (response) {
                            this.code = response.data
                        }.bind(this))
                    }
                }
            })