<template>
    <div>
        <p> Works </p>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                loading: false,
                exchanges: [],
                error: false
            }
        },
        mounted() {
            this.loading = true
            axios.get('http://127.0.0.1:8000/exchanges/exchange_list').then(res => {
                this.loading = false
                this.exchanges = res.data.exchanges
            }).catch(err => {
                this.loading = false
                this.error = true
            })
        }
    }
</script>
