export default {
    data() {
        return {
            api: 'http://127.0.0.1:8000/api/v1',
            // api: 'https://binance.wiroon.co.th/api/v1',
            binance_api: 'https://api.binance.com/api/v3/avgPrice?symbol=',
            coins: []
        }
    },
    created() {
        this.getCoin()
    },
    methods: {
        async getCoin() {
            let response = await axios.get(`${this.api}/status/coin`)
            await this.getCoinStatus(response.data.data)
        },
        async getCoinStatus(res) {
            // console.log(res)
            // res.forEach(async coin => {
            //     let response = await axios.get(`${this.binance_api}DDFUSDT`)
            //     console.log(response)
            // })
            axios.get(`${this.binance_api}DDFUSDT`)
            .then((dds) => {
                console.log(dds)
            })
            .catch((error) => {
                console.log(error.response)
                console.log(error.request)
                console.log(error.message)
            })
        }
    },
    template: `<div>
        <div class="row">
            <div class="col-md-12">
                <h4>Hello.....</h4>
            </div>
        </div>
    </div>`
}