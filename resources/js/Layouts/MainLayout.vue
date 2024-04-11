<template>
    <div class="w-full">
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
            <Link :href="route('dashboard')" :active="route().current('dashboard')">
                Dashboard
            </Link>
            <Link :href="route('products.index')" :active="route().current('products.index')">
                Products
            </Link>
        </div>
        <slot />
    </div>
</template>

<script >
    import { Link } from '@inertiajs/vue3';
    export default {
        name: "MainLayout",

        components: {
            Link
        }
    }
</script>

<style scoped>

</style>
