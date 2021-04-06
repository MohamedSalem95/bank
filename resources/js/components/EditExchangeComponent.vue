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
                <td> {{ exchange.rate }} </td>
                <td>
                    <input value="exchange.sellMargin" style="width: 100px" type="text" v-model="exchange.sell_margin" class="form-control d-inline">
                    {{ compSell}}
                </td>
                <td>
                    <input style="width: 100px" type="text" v-model="exchange.buy_margin" class="form-control d-inline">
                    {{ compBuy }}
                </td>
                <td>
                    <button v-if="error" class="btn btn-sm btn-success" disabled> Update </button>
                    <button v-if="!error && !saving" class="btn btn-sm btn-success" > Update </button>
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
            <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
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
            exchange: {}
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
            this.saving = true
            let updateData = {
                from: this.exchange.from,
                to: this.exchange.to,
                rate: this.exchange.rate,
                sell_margin: this.exchange.sell_margin,
                sell: this.compSell,
                buy_margin: this.exchange.buy_margin,
                buy: this.compBuy
            }
            axios.put(`${baseUrl}/exchanges/${this.id}`, updateData).then(res => {
                this.saving = false
                if(res.data.status === 'success') this.success = true
                setTimeout(() => { this.success = false }, 3000)
                this.clearForm()
            }).catch(err => {
                this.saving = false
                console.log('error')
            })
        },
        clearForm () {
            this.exchange = {}
        } 
    }
}
</script>