<template>
    <div class="home">
        <div class="home__header">
            <div class="container">
                <v-navigation></v-navigation>
                <div class="home__welcome">
                    <div class="home__welcome-inside">
                        <h1 class="home__title" v-html="title"></h1>
                        <carousel class="home__servers"
                                  :autoplay="true"
                                  :autoplayTimeout="3000"
                                  :autoplayHoverPause="true"
                                  :navigationEnabled="false"
                                  :paginationEnabled="false"
                                  :perPage="4"
                                  :perPageCustom="[[1440,4],[1024,3],[768,2],[425,1],[375,1],[320,1]]"
                                  :speed="1000">
                            <slide class="home__server" v-for="(item, index) in servers" :key="index">
                                <div class="home__server-wrap">
                                    <span class="home__server-name">{{ item['HostName'] }}</span>
                                    <span class="home__server-online">{{ item['Players'] }}/{{ item['MaxPlayers'] }}</span>
                                    <span class="home__server-line">
                                        <i :style="'width:'+getOnlinePercent(item['Players'], item['MaxPlayers'])+'%;'"></i>
                                    </span>
                                    <span class="home__server-address" @click="copyAddress($event, item['address'])">
                                        {{ item['address'] }}
                                        <span>Копировать</span>
                                    </span>
                                </div>
                            </slide>
                        </carousel>
                        <div class="home__copyright">
                            <span class="home__copyright-special" v-html="copyright"></span>
                            <span class="home__copyright-creator">
                                Created & Design by <a href="https://vk.com/r.keldikov" target="_blank">Samuello#</a>.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input id="clipboard" type="hidden" :value="clipboard">
    </div>
</template>

<script>
    import Axios from "axios";

    export default {
        name: "VHome",
        data() {
            return {
                title: '',
                servers: [],
                copyright: '',
                clipboard: ''
            }
        },
        mounted() {
            this.getServersData();
            this.getCopyright();
            this.getTitle();
        },
        methods: {
            getServersData() {
                Axios.get('/api/appdata/get?section=servers').then(response => {
                    const addresses = response.data;
                    addresses.forEach(address => {
                        const url = `/api/serverdata/get?address=${address.address}&platform=${address.platform}`;
                        Axios.get(url).then(response => {
                            response.data.address = address.address;
                            this.servers.push(response.data);
                        });
                    })
                });
            },
            getCopyright() {
                Axios.get('/api/appdata/get?section=copyright').then(response => {
                    this.copyright = response.data;
                });
            },
            getTitle() {
                Axios.get('/api/appdata/get?section=title').then(response => {
                    this.title = response.data;
                });
            },
            getOnlinePercent(players, maxPlayers) {
                return players * 100 / maxPlayers;
            },
            copyAddress(event, address)     {
                const clipboard = document.querySelector('#clipboard');
                clipboard.value = address;
                clipboard.setAttribute('type', 'text');
                clipboard.select();

                try {
                    const successfully = document.execCommand('copy');
                    const message = successfully ? 'Скопировано' : 'Копировать ';

                    event.target.childNodes[1].classList.add('active');
                    event.target.childNodes[1].textContent = message;

                    setTimeout(() => {
                        event.target.childNodes[1].classList.remove('active');
                        event.target.childNodes[1].textContent = 'Копировать';
                    }, 1000);

                } catch (error) {
                    alert('Ошибка копирования, возможно ваш браузер не поддерживает этот функционал.')
                }

                clipboard.setAttribute('type', 'hidden');
                window.getSelection().removeAllRanges();
            }
        }
    }
</script>

<style lang="scss">
    .home {
        &__header {
            width: 100%;
            height: 100vh;
            background-image: url('/public/images/bg.jpg');
            background-position: center center;
            background-size: cover;
        }
        &__welcome {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            width: 100%;
            height: calc(100vh - 200px);

            &-inside {
                width: 100%;
            }
        }
        &__title {
            display: inline-block;
            margin: 0;
            padding: 0 0 2rem 0;
            color: #fff;
            font-weight: 700;
            text-shadow: 0 0 1rem rgba(#010103, 1);
        }
        &__servers {
            margin: 0 auto;
            transform: translateY(150px);

            @media (max-width: 1024px) {
                transform: translateY(100px);
            }

            @media (max-width: 425px) {
                transform: translateY(50px);
            }
        }
        &__server {

            @media (min-width: 425px) {
                padding: 0 1rem;
            }

            &-wrap {
                padding: 1rem 1.5rem;
                background-color: #010103;
                border-radius: 1rem;
                transition: .5s ease;
                cursor: pointer;
            }
            span {
                display: inline-block;
                width: 100%;
                margin: 0;
                padding: 0;
                color: #fff;
            }
            &-name {
                height: 2.5rem;
                font-size: .9rem;
                font-weight: 600;
                line-height: 1.1rem;
            }
            &-online {
                font-size: 1rem;
                font-weight: 500;
                letter-spacing: 2px;
            }
            &-line {
                height: 2px;
                margin: .5rem 0 .3rem 0 !important;
                background-color: rgba(#090979,.2);
                position: relative;

                i {
                    display: inline-block;
                    width: 0;
                    height: 2px;
                    background-color: #b300ff;
                    position: absolute;
                    top: 0;
                    left: 0;
                }
            }
            &-address {
                font-size: .8rem;
                font-weight: 600;
                text-align: center;
                letter-spacing: 1px;
                background-color: rgba(#fff,.1);
                border-radius: 1rem;
                overflow: hidden;
                position: relative;

                &:hover {
                    span {
                        opacity: 1;
                        visibility: visible;
                    }
                }

                span {
                    width: 100%;
                    height: 100%;
                    text-align: center;
                    background-color: #b300ff;
                    opacity: 0;
                    visibility: hidden;
                    transition: .15s ease;
                    pointer-events: none;
                    position: absolute;
                    top: 0;
                    left: 0;

                    &.active {
                        background-color: #090979;
                        opacity: 1;
                        visibility: visible;
                    }
                }
            }
        }
        &__copyright {
            display: flex;
            justify-content: space-between;
            align-items: center;
            transform: translateY(300px);

            span {
                color: #fff;
                font-size: .9rem;
                font-weight: 500;
            }

            @media (max-width: 1024px) {
                transform: translateY(250px);
            }

            @media (max-width: 425px) {
                flex-direction: column;
                text-align: left;
                transform: translateY(200px);

                span {
                    line-height: 1.5rem;
                }
            }
        }
    }
</style>
