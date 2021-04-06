<template>
    <div>
        <form method="post" @submit="submitForm($event)">

            <table class="table table-sm table-bordered">
            <thead>
                <th> From </th>
                <th> To </th>
                <th> Rate </th>
                <th> Sell </th>
                <th> Buy </th>
                <th> Action </th>
            </thead>
            <tbody>
                <td> {{ exchange.from }} </td>
                <td> {{ exchange.to }} </td>
                <td> 
                    <div v-if="loadingRate" class="spinner-border spinner-border-sm" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <b v-if="liveRateError" class="text-danger"> Error </b>
                    <span v-if="exchange.rate != 0"> {{ exchange.rate }} </span>

                    <br>
                    <small style="cursor: pointer" @click="pullLiveRate()"> 
                        <b class="text-info"> Pull live rate </b>
                    </small>
                    <br>
                    <b class="text-danger">
                        {{ fieldErrors.rate }}
                    </b>
                </td>
                <td>
                    <input style="width: 100px" type="text" v-model="exchange.sell_margin" class="form-control d-inline">
                    {{ compSell}}
                    <br>
                    <b class="text-danger">
                        {{ fieldErrors.sell_margin }}
                    </b>
                </td>
                <td>
                    <input style="width: 100px" type="text" v-model="exchange.buy_margin" class="form-control d-inline">
                    {{ compBuy }}
                    <br>
                    <b class="text-danger">
                        {{ fieldErrors.buy_margin }}
                    </b>
                </td>
                <td>
                    <button v-if="error || updated" class="btn btn-sm btn-success" disabled> Update </button>
                    <button v-if="!error && !saving && !updated" class="btn btn-sm btn-success" > Update </button>
                    <button v-if="saving" class="btn btn-sm btn-success" disabled>
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Updating
                    </button>
                </td>
            </tbody>
        </table>
        <p class="text-success" v-if="success">
            <b> Object Updated successfully. </b>
        </p>

        </form>
        <h5 v-if="loading">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Loading The Exchange Please Wait.
        </h5>
        <h5 v-if="error" class="text-danger"> We couldnit load the object Please reload the page </h5>
    </div>
</template>

<script>
let baseUrl = 'http://127.0.0.1:8000'
export default {
    props: {
        id: Number
    },
    data () {
        return {
            loading: false,
            error: false,
            saving: false,
            success: false,
            loadingRate: false,
            liveRateError: false,
            updated: false,
            exchange: {},
            fieldErrors: {
                rate: '',
                sell_margin: '',
                buy_margin: ''
            }
        }
    },
    mounted () {
        this.loading = true
        axios.get(`${baseUrl}/exchanges/${this.id}`).then(res => {
            this.loading = false
            this.exchange = res.data
            // console.log(res)
        }).catch(err => {
            this.error = true
        })
    },
    computed: {
        compSell () {
            return parseFloat(this.exchange.rate) + parseFloat(this.exchange.sell_margin)
        },
        compBuy () {
            return parseFloat(this.exchange.rate) + parseFloat(this.exchange.buy_margin)
        }
    },
    methods :{
        submitForm (event) {
            event.preventDefault()
            let updateData = {
                from: this.exchange.from,
                to: this.exchange.to,
                rate: this.exchange.rate,
                sell_margin: this.exchange.sell_margin,
                sell: this.compSell,
                buy_margin: this.exchange.buy_margin,
                buy: this.compBuy
            }
            this.fieldErrors = this.validateForm(updateData)
            if(Object.keys(this.fieldErrors).length) return
            
            this.saving = true

            axios.put(`${baseUrl}/exchanges/${this.id}`, updateData).then(res => {
                this.saving = false
                if(res.data.status === 'success') {
                    this.success = true
                    this.updated = true
                    setTimeout(() => { this.success = false }, 3000)
                    this.clearForm()
                }
                
            }).catch(err => {
                this.saving = false
                console.log('error')
            })
        },
        validateForm (fields) {
            let errors = {}
            if(fields.rate === 0) errors.rate = "Invalid Rate"
            if(fields.sell_margin === '') errors.rate = "Invalid Margin"
            if(fields.buy_margin === '') errors.rate = "Invalid Margin"
            
            return errors
        },
        clearForm () {
            this.exchange = {}
        },
        pullLiveRate () {
            this.exchange.rate = 0
            this.loadingRate = true
            axios.get(
                `https://xecdapi.xe.com/v1/convert_from.json/?from=${this.exchange.from}&to=${this.exchange.to}&amount=1`,
                { auth: { username: 'mohamedsalem35114584', password: 'nbnpgpn51ult5f8259vgn8kei' } }
            ).then(res => {
                this.loadingRate = false
                this.liveRateError = false
                this.exchange.rate = res.data.to[0].mid
            }).catch(err => {
                this.loadingRate = false
                this.exchange.rate = 0
                this.liveRateError = true
            })
        }
    }
}
</script>