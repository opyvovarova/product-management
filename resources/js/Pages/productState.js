import { reactive } from "vue";

const state = reactive({
    products: [
        {
            name: '',
            quantity: null,
            price: null,
        }
    ],
    loading: false,
    error: null,
    errors: {}
});

export default state;
