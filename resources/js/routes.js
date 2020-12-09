export default [
    { path: "/", component: require("./components/VHome").default },
    { path: "*", component: require("./components/VPageNotFound").default },
];
