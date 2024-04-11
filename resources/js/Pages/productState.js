import { reactive } from "vue";

const state = reactive({
    products: [],
    name: '',
    loading: false,
    error: null,
    quantity: null,
    price: null,
    errors: {}
});

export default state;
