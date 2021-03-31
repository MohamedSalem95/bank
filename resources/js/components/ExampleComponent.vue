<template>
    <div class="">
       <p v-if="loading">
           <b class="text-muted"> Fetching Currencies Please Wait ... </b>
       </p>
       <div v-if="currencies.length != 0">
           <table class="table table-sm table-striped table-bordered table-hover">
               <thead>
                   <th> Currency </th>
                   <th> Currency Name </th>
               </thead>

               <tbody>
                    <tr v-for="currency in currencies" :key="currency.iso">
                            <td> {{ currency.iso }} </td>
                            <td> {{ currency.currency_name }} </td>
                    </tr>
               </tbody>
           </table>
       </div>
           
       <p v-if="error">
           <b class="text-danger"> Some error Happended please Reload Page! </b>
       </p>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                loading: false,
                currencies: [],
                error: false
            }
        },
        mounted() {
            this.loading = true
            axios.get('https://xecdapi.xe.com/v1/currencies.json?obsolete=true', {
                auth: {
                    username: 'mohamedsalem35114584',
                    password: 'nbnpgpn51ult5f8259vgn8kei'
                },
                headers: {
                    //'Access-Control-Allow-Origin': '*'
                }
            }).then(res => {
                this.loading = false
                this.currencies = res.data.currencies
                console.log(res.data.currencies)
            }).catch(err => {
                this.loading = false
                this.error = true
            })
        }
    }
</script>
