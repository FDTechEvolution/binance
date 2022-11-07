export default {
    data() {
        return {
            // api: 'http://127.0.0.1:8000/api/v1',
            api: 'https://binance.wiroon.co.th/api/v1',
            lists: '',
            speed: 60000, // 1 minute
            search: '',
            search_lists: ''
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
                this.searchData()
            })
            .catch(e => {
                console.log(e)
            })
        },
        isPercent(percent) {
            if(percent > 0) return 'text-success'
            else if(percent < 0) return 'text-danger'
        },
        searchData() {
            if(this.search !== '') {
                this.search_lists = this.lists.filter(list => list.symbol.includes(this.search.toUpperCase()));
            }else{
                this.search_lists = ''
            }
        }
    },
    template: `<div>
        <div class="row mb-2">
            <div class="col-4 d-flex">
                <input v-model="search" type="text" class="form-control border rounded px-2" placeholder="ค้นหา..." @keyup="searchData()">
            </div>
        </div>
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
            <slot v-if="search_lists === ''">
                <tr v-for="(list, index) in lists" :key="index">
                    <td class="text-center">{{ index+1 }}</td>
                    <td><a href="https://www.binance.com/en/futures/{{list.symbol}}?_from=markets" target="_blank">{{ list.symbol }}</a></td>
                    <td class="text-center">{{ list.last_price }}</td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.m_2ch)">{{ list.m_2ch }}%</p> <small>{{list.m_2}}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.m_5ch)">{{ list.m_5ch }}%</p> <small>{{ list.m_5 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.m_10ch)">{{ list.m_10ch }}%</p> <small>{{ list.m_10 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.m_30ch)">{{ list.m_30ch }}%</p> <small>{{ list.m_30 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.h_1ch)">{{ list.h_1ch }}%</p> <small>{{ list.h_1 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.h_4ch)">{{ list.h_4ch }}%</p> <small>{{ list.h_4 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.h_24ch)">{{ list.h_24ch }}%</p> <small>{{ list.h_24 }}</small>
                    </td>
                </tr>
            </slot>
            <slot v-else>
                <tr v-for="(list, index2) in search_lists" :key="index2">
                    <td class="text-center">{{ index2+1 }}</td>
                    <td><a href="https://www.binance.com/en/futures/{{list.symbol}}?_from=markets" target="_blank">{{ list.symbol }}</a></td>
                    <td class="text-center">{{ list.last_price }}</td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.m_2ch)">{{ list.m_2ch }}%</p> <small>{{list.m_2}}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.m_5ch)">{{ list.m_5ch }}%</p> <small>{{ list.m_5 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.m_10ch)">{{ list.m_10ch }}%</p> <small>{{ list.m_10 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.m_30ch)">{{ list.m_30ch }}%</p> <small>{{ list.m_30 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.h_1ch)">{{ list.h_1ch }}%</p> <small>{{ list.h_1 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.h_4ch)">{{ list.h_4ch }}%</p> <small>{{ list.h_4 }}</small>
                    </td>
                    <td class="text-center">
                        <p class="mb-0" :class="isPercent(list.h_24ch)">{{ list.h_24ch }}%</p> <small>{{ list.h_24 }}</small>
                    </td>
                </tr>
            </slot>
        </table>
    </div>`
}