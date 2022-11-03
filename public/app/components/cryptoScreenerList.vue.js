export default {
    data() {
        return {
            // api: 'http://127.0.0.1:8000/api/v1',
            api: 'https://binance.wiroon.co.th/api/v1',
            lists: '',
            speed: 60000, // 1 minute
        }
    },
    created() {
        this.updateCryptoScreener()
    },
    mounted() {
        setInterval(() => {
            this.updateCryptoScreener()
        }, this.speed)
    },
    methods: {
        updateCryptoScreener() {
            axios.get(`${this.api}/crypto-screener/list`)
            .then((res) => {
                // console.log(res.data.data)
                this.lists = res.data.data
                // console.log(res.data.data)
            })
            .catch(e => {
                console.log(e)
            })
        },
        isPercent(last_price, is_price) {
            let num = ((last_price - is_price)/is_price)*100
            return num.toFixed(3)
        }
    },
    template: `<div>
        <table id="datatable" class="table table-bordered">
            <thead>
                <tr class="bg-secondary text-light">
                    <th class="text-center"><small>#</small></th>
                    <th>Name</th>
                    <th class="text-center">Last Price</th>
                    <th class="text-center">2min(ch%)</th>
                    <th class="text-center">5min(ch%)</th>
                    <th class="text-center">10min(ch%)</th>
                    <th class="text-center">30min(ch%)</th>
                    <th class="text-center">1H(ch%)</th>
                    <th class="text-center">4H(ch%)</th>
                    <th class="text-center">24H(ch%)</th>
                </tr>
            </thead>
            <tr v-for="(list, index) in lists" :key="index">
                <td class="text-center">{{ index+1 }}</td>
                <td>{{ list.symbol }}</td>
                <td class="text-center">{{ list.last_price }}</td>
                <td class="text-center">{{ list.m_2 }} ({{ isPercent(list.last_price, list.m_2) }}%)</td>
                <td class="text-center">{{ list.m_5 }} ({{ isPercent(list.last_price, list.m_5) }}%)</td>
                <td class="text-center">{{ list.m_10 }} ({{ isPercent(list.last_price, list.m_10) }}%)</td>
                <td class="text-center">{{ list.m_30 }} ({{ isPercent(list.last_price, list.m_30) }}%)</td>
                <td class="text-center">{{ list.h_1 }} ({{ isPercent(list.last_price, list.h_1) }}%)</td>
                <td class="text-center">{{ list.h_4 }} ({{ isPercent(list.last_price, list.h_4) }}%)</td>
                <td class="text-center">{{ list.h_24 }} ({{ isPercent(list.last_price, list.h_24) }}%)</td>
            </tr>
        </table>
    </div>`
}