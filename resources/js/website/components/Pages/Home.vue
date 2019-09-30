<template>
    <section>
        <home-banner></home-banner>

        <welcome></welcome>
        
        <Testimonials></Testimonials>

        <Cities></Cities>

        <Feature></Feature>

        <Clients></Clients>
    </section>
</template>

<script>
    import homeBanner from './Home/home_banner.vue';
    import welcome from './Home/welcome.vue';
    import Testimonials from './Home/Testimonials.vue';
    import Cities from './Home/Cities.vue';
    import Feature from './Home/Feature.vue';
    import Clients from './Home/Clients.vue';

    export default {
        metaInfo() {
            return {
                title : 'صفحه اصلی' ,
            }
        } ,

        components: {
            homeBanner ,
            welcome , 
            Testimonials ,
            Cities ,
            Feature ,
            Clients
        }
    }
</script>
