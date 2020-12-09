<template>
    <div class="navigation">
        <div class="navigation__inside">
            <div class="navigation__content">
                <router-link class="navigation__logo" to="/">
                    <img src="/public/images/logo.png" alt="logo">
                </router-link>
                <ul class="navigation__links">
                    <span></span>
                    <li v-for="(item, index) in navigation" :key="index">
                        <router-link :to="item['link']" v-if="item['is_spa']">{{ item['text'] }}</router-link>
                        <a :href="item['link']" v-else>{{ item['text'] }}</a>
                    </li>
                </ul>
                <div class="navigation__mobile" @click="toggleNavigation"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import Axios from "axios";

    export default {
        name: "VNavigation",
        data() {
            return {
                navigation: [],
            }
        },
        mounted() {
            this.getNavigation();

            const navigation = document.querySelector('.navigation__links');
            console.log(navigation.childNodes);

            navigation.addEventListener('click', () => {
                this.toggleNavigation();
            })
        },
        methods: {
            getNavigation() {
                Axios.get('/api/appdata/get?section=navigation').then(response => {
                    this.navigation = response.data;
                });
            },
            toggleNavigation() {
                document.querySelector('.navigation__links').classList.toggle('active');
                document.querySelector('.navigation__links span').classList.toggle('active');
            }
        }
    }
</script>

<style lang="scss">
    .navigation {
        &__inside {
            display: flex;
            align-items: center;
            width: 100%;
            height: 100px;
            padding: 1rem 0;
        }
        &__content {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        &__logo {
            img {
                height: 30px;
            }
        }
        &__links {
            margin: 0 -1.5rem 0 0;
            padding: 0;
            list-style-type: none;

            span {
                display: none;
            }

            li {
                margin: 0;
                padding: 0 .5rem;
                display: inline-block;

                a {
                    padding: .5rem;
                    color: #fff;
                    font-size: .9rem;
                    font-weight: 600;
                    position: relative;

                    &::before {
                        content: '';
                        display: inline-block;
                        width: 0;
                        height: 3px;
                        background-color: #0750D0;
                        transform: translateX(-50%) skewX(-55deg);
                        transition: .5s ease;
                        position: absolute;
                        left: 50%;
                        bottom: 0;
                    }

                    &:hover, &.router-link-active {
                        &::before {
                            width: 50%;
                        }
                    }
                }
            }

            @media (max-width: 1024px) {
                display: flex;
                justify-content: center;
                flex-direction: column;
                align-items: center;
                width: 100%;
                height: 100vh;
                background-color: #010103;
                transform: translateX(-100vw);
                transition: all 2s ease;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;


                span {
                    display: block;
                    width: 35px;
                    height: 35px;
                    background-image: url('/public/images/close.png');
                    background-position: center center;
                    background-size: cover;
                    transform: translateX(-100vw);
                    transition: all 2s ease;
                    position: fixed;
                    top: 2rem;
                    right: 2rem;
                    z-index: 2000;
                }

                &.active, &.active span {
                    transform: translateX(0);
                }

                li {
                    display: block;
                    padding: 1rem .5rem;
                    text-align: left;
                }
            }
        }
        &__mobile {
            display: none;
            width: 35px;
            height: 35px;
            background-image: url('/public/images/menu.png');
            background-position: center center;
            background-size: cover;

            @media (max-width: 1024px) {
                display: block;
            }
        }
    }
</style>
