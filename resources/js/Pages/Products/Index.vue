<template>
    <div class="flex justify-between items-center h-screen">
        <div class="w-full">
            <div class="bg-white shadow-md rounded-lg p-4 mb-4">
                <div class="bg-violet-800 text-white p-2 rounded-t-lg">
                    <h2 class="text-xl fond-semibold text-sm">Новая закупка</h2>
                </div>

                <div v-if="validationError" class="bg-red-500 text-white p-2 rounded mt-4">
                    Пожалуйста, заполните все обязательные поля.
                </div>

                <div v-if="saveSuccess" class="bg-green-500 text-white p-2 rounded mt-4">
                    Все данные успешно сохранены
                </div>

                <div class="p-4">

                    <div class="bg-white shadow-md rounded-lg p-4">

                        <div class="bg-cyan-500 text-white p-2 rounded-t-lg">

                            <h2 class="text-xl font-bold text-xs">Товарные позиции</h2>
                        </div>

                        <div class="p-4">
                            <table class="w-full">
                                <thead>
                                <tr>
                                    <th>Наименование продукта</th>
                                    <th>Количество шт</th>
                                    <th>Стоимость продукта Руб</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(newProduct, index) in newProducts" :key="index">
                                        <td>
                                            <input class="form-control" type="text" v-model="newProduct.name" />
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" v-model="newProduct.quantity" />
                                        </td>
                                        <td>
                                            <input class="form-control text-right" type="number" min="0" step=".01" v-model="newProduct.price" />
                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr v-for="(product, k) in state.products" :key="product">
                                        <td>
                                            <input class="form-control" type="text" v-model="product.name" />
                                            <span v-if="product.error && product.error.name" class="text-red-500">{{ product.error.name }}</span>
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" v-model="product.quantity" />
                                            <span v-if="product.error && product.error.quantity" class="text-red-500">{{ product.error.quantity }}</span>

                                        </td>
                                        <td>
                                            <input class="form-control text-right" type="number" min="0" step=".01" v-model="product.price" />
                                            <span v-if="product.error && product.error.price" class="text-red-500">{{ product.error.price }}</span>

                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                            <button @click="deleteRow(k)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Удалить</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="flex justify-center mt-4">
                                <button @click="addNewRow" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-start p-4">
                    <button @click.prevent="cancelProduct" class="bg-gray-500 text-white hover:bg-gray-400 px-4 py-2 mr-1 rounded">Отмена</button>
                    <button @click.prevent="saveData" class="bg-green-700 hover:bg-green-500 text-white px-4 py-2 rounded">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import state from "@/Pages/productState.js";
import MainLayout from "@/Layouts/MainLayout.vue";
import { inject } from 'vue';

export default {
    name: "Index",
    layout: MainLayout,
    data() {
        return {
            showInputs: false,
            state: state,
            saveSuccess: false,
            newProducts: [],
            validationError: false,
            apiUrl: 'http://localhost:8000'
        }
    },
    // setup() {
    //     const apiUrl = inject('apiUrl');
    //     return {
    //         apiUrl,
    //         state
    //     }
    // },

    methods: {
        async saveData() {
            try {
                if (this.state.products.length === 0) {
                    this.validationError = true;
                    return;
                }

                const isValid = this.validateData();
                if (!isValid) {
                    this.validationError = true;
                    return;
                }

                for (const product of this.state.products) {
                    await axios.post(`${this.apiUrl}/products`, {
                        name: product.name,
                        quantity: product.quantity,
                        price: product.price
                    });
                }

                    this.newProducts.push(...this.state.products);


                this.clearInputs();

                this.saveSuccess = true;
                this.validationError = false;

                this.showInputs = false;
                setTimeout(() => {
                    this.saveSuccess = false;
                }, 3000);
            } catch (error) {
                console.error('Error saving data: ', error);
            }
        },

        deleteRow(index) {
            this.state.products.splice(index, 1);
        },
        addNewRow() {

            this.state.products.push({
                name: '',
                quantity: null,
                price: null
            });
            this.saveSuccess = false;
        },
        validateData() {
            let isValid = true;

            for (let product of this.state.products) {
                product.error = {};
                if (!product.name) {
                    product.error.name = 'Поле "Наименование продукта" обязательно для заполнения';
                    isValid = false;
                }
                if (!product.quantity) {
                    product.error.quantity = 'Поле "Количество шт" обязательно для заполнения';
                    isValid = false;
                }
                if (!product.price) {
                    product.error.price = 'Поле "Стоимость продукта" обязательно для заполнения';
                    isValid = false;
                }
            }
            return isValid;
        },

        async cancelProduct(productId) {
            try {
                await axios.delete(`${this.apiUrl}/products/${productId}`);
                await this.fetchProducts();
                if (this.state.products.length === 0) {
                    this.showInputs = false;
                }

            } catch (error) {
                console.error('Error deleting product: ', error);
            }

        },

        clearInputs() {
            this.state.products = [];
        },
        async fetchProducts() {
            console.log('Fetching products...');
            try {
                const response = await axios.get('/products', {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    }
                });
                return response.data;
                console.log(response.data);
            } catch (error) {
                console.error('Error fetching products: ', error);
                return []; // Возвращаем пустой массив в случае ошибки
            }
        },
        // async fetchProducts() {
        //     console.log('Fetching products...');
        //     await axios.get('/products', {
        //         headers: {
        //             "X-Requested-With": "XMLHttpRequest",
        //             "Accept": "application/json"
        //         }
        //     }).then(response => {
        //         return response.data;
        //     }).catch(error => {
        //         console.log(error);
        //     });
        // },
    }
}
</script>
