<template>
    <div>
        <Blog-Category></Blog-Category>

        <Blog-Area></Blog-Area>
    </div>
</template>

<script>
    import BlogCategory from './Blog/Blog-Category.vue';
    import BlogArea from './Blog/Blog-Area.vue';

    export default {
        components: {
            BlogCategory ,
            BlogArea
        }    
    }
</script>