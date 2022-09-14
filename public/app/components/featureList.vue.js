export default {
    data() {
        return {
            api: 'http://127.0.0.1:8000/api/v1',
            // api: 'https://binance.wiroon.co.th/api/v1',
            lists: [],
            speed: 60000, // 1 minute
            errorMsg: ''
        }
    },
    created() {
        this.updateAVGPrice()
    },
    mounted() {
        setInterval(() => {
            this.updateAVGPrice()
        }, this.speed)
    },
    methods: {
        updateAVGPrice() {
            axios.get(`${this.api}/update-avg-price`)
            .then(() => {
                this.showAVGPrice()
            })
            .catch(() => {
                this.errorMsg = 'เกิดข้อผิดพลาดในการอัพเดทเหรียญ...'
            })
        },
        async showAVGPrice() {
            await axios.get(`${this.api}/features/list`)
                    .then((response) => {
                        this.lists = response.data.data
                        this.startBar()
                        this.hilightPrice(response.data.data)
                    })
            await this.calculatePercent()
        },
        calculatePercent() {
            this.lists.forEach((coin, index) => {
                if(coin.status === 'WATCH' || coin.status === 'OPEN'){
                    let showPercent = document.querySelector(`#avg-price-percent-${index}`)
                    let calPercent = Math.round(coin.avg_price * 100) >= Math.round(coin.entry1 * 100) ? true : false
                    let percent = calPercent ? (coin.avg_price - coin.entry1) / coin.entry1 : (coin.entry1 - coin.avg_price) / coin.avg_price
                    if(coin.type === 'LONG') {
                        if(calPercent) {
                            showPercent.classList.add('text-success')
                            showPercent.classList.remove('text-danger')
                            showPercent.innerHTML = `+${percent.toFixed(2)}%`
                        }else{
                            showPercent.classList.add('text-danger')
                            showPercent.classList.remove('text-success')
                            showPercent.innerHTML = `-${percent.toFixed(2)}%`
                        }
                    }else if(coin.type === 'SHORT') {
                        if(calPercent) {
                            showPercent.classList.add('text-danger')
                            showPercent.classList.remove('text-success')
                            showPercent.innerHTML = `-${percent.toFixed(2)}%`
                        }else{
                            showPercent.classList.add('text-success')
                            showPercent.classList.remove('text-danger')
                            showPercent.innerHTML = `+${percent.toFixed(2)}%`
                        }
                    }
                }
            })
        },
        startBar() {
            let width = 1
            let counterBar = setInterval(() => {
                if(width >= 100) {
                    clearInterval(counterBar)
                }else{
                    width++
                    document.querySelector("#is-bar").style.width = width+ "%"
                }
            }, (this.speed / 100))
        },
        hilightPrice(data) {
            data.forEach((coin, index) => {
                if(coin.status === 'WATCH' || coin.status === 'OPEN'){
                    let is_class = document.querySelector(`.avg-price-${index}`)
                    if(is_class !== null) {
                        is_class.classList.add('avg-hilight')
                        is_class.classList.add('text-primary')
                    }
                }
            })

            setTimeout(() => {
                data.forEach((coin, index) => {
                    if(coin.status === 'WATCH' || coin.status === 'OPEN'){
                        document.querySelector(`.avg-price-${index}`).classList.remove('avg-hilight')
                        document.querySelector(`.avg-price-${index}`).classList.remove('text-primary')
                    }
                })
            }, 2000)

        },
        setTH_Date(date) {
            let ex_date = date.split(' ')[0].split('-')
            let ex_time = date.split(' ')[1].split(':')

            return `${ex_date[2]}/${ex_date[1]}/${ex_date[0]} ${ex_time[0]}:${ex_time[1]}`
        },
        setFormatNumber(num) {
            if(num) {
                let beforeDigit = num.split('.')[0]
                let afterDigit = num.split('.')[1]

                return this.formatNumber(beforeDigit)+'.'+afterDigit
            }else return '0.00000'
        },
        formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        },
        featureEdit(id) {
            this.lists.find(list => {
                if(list.id === id) {
                    let ex_date = list.docdate.split(' ')[0].split('-')
                    let ex_time = list.docdate.split(' ')[1].split(':')
                    let setDate = `${ex_date[0]}-${ex_date[1]}-${ex_date[2]} ${ex_time[0]}:${ex_time[1]}`

                    const idList = ['feature-e-id', 'feature-e-coin', 'feature-e-type', 'feature-e-stop_loss', 
                                    'feature-e-usdt_pnl', 'feature-e-docdate', 'feature-e-status', 'feature-e-description', 
                                    'feature-e-entry1', 'feature-e-entry2', 'feature-e-entry3', 'feature-e-target1', 'feature-e-target2',
                                    'feature-e-target3'
                                ]
                    const valueList = [list.id, list.coin_name, list.type, list.stop_loss, list.usdt_pnl, setDate, 
                                        list.status, list.description, list.entry1, list.entry2, list.entry3, list.target1, list.target2, list.target3
                                ]

                    document.querySelector('#coin-name-edit-title').innerHTML = list.coin_name
                    idList.forEach((e_id, index) => {
                        document.querySelector(`#${e_id}`).value = valueList[index]
                    })
                }
            })
        },
        confirmDeleteFeature(id, coin, type, status) {
            document.querySelector('#delete-coin-name').innerHTML = coin
            document.querySelector('#delete-coin-type').innerHTML = type
            document.querySelector('#delete-coin-status').innerHTML = status
            document.querySelector('#feature-id-delete').value = id
        }
    },
    template: `<div>
        <div id="is-progress">
            <div id="is-bar"></div>
        </div>
        <table id="datatable" class="table table-bordered">
            <thead>
                <tr class="bg-secondary text-light">
                    <th class="text-center"><small>#</small></th>
                    <th>Coin</th>
                    <th class="text-center">Type</th>
                    <th class="text-right">Entry</th>
                    <th class="text-right">Target</th>
                    <th class="text-right">Stop Loss</th>
                    <th class="text-right">AVG Price</th>
                    <th class="text-right">USDT PNL</th>
                    <th class="text-center">Percent</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Docdate</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tr v-if="errorMsg !== ''">
                <td colspan="11" class="text-center">
                    {{ errorMsg }}
                </td>
            </tr>
            <tr v-else v-for="(list, index) in lists" :key="index">
                <td class="text-center"><small>{{ index+1 }}</small></td>
                <td>{{ list.coin_name }}</td>
                <td class="text-center">{{ list.type }}</td>
                <td class="text-right">{{ setFormatNumber(list.entry1) }}</td>
                <td class="text-right">{{ setFormatNumber(list.target1) }}</td>
                <td class="text-right">{{ setFormatNumber(list.stop_loss) }}</td>
                <td class="text-right" :class="'avg-price-'+index">{{ setFormatNumber(list.avg_price) }}</td>
                <td class="text-right">{{ setFormatNumber(list.usdt_pnl) }}</td>
                <td class="text-center"><span :id="'avg-price-percent-'+index"></span></td>
                <td class="text-center">
                    <span v-if="list.status === 'WATCH'" class="badge badge-primary">{{ list.status }}</span>
                    <span v-if="list.status === 'OPEN'" class="badge badge-success">{{ list.status }}</span>
                    <span v-if="list.status === 'CLOSE'" class="badge badge-danger">{{ list.status }}</span>
                    <span v-if="list.status === 'STOPLOSS'" class="badge badge-warning">{{ list.status }}</span>
                </td>
                <td class="text-center"><small>{{ setTH_Date(list.docdate) }}</small></td>
                <td class="text-center">
                    <i class="ion-edit text-dark px-1 icon-on-hover" 
                        data-toggle="modal" data-target="#featureEditModal" 
                        @click="featureEdit(list.id)">
                    </i>
                    <i class="ion-trash-a text-dark px-1 ml-2 icon-on-hover" 
                        data-toggle="modal" data-target="#featureConfirmDelete" 
                        @click="confirmDeleteFeature(list.id, list.coin_name, list.type, list.status)">
                    </i>
                </td>
            </tr>
        </table>
    </div>`
}