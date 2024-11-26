<template>
    <diV>

    <h1>Activity</h1>
        <!-- binding of variable -->
         <br>
        <input type="text" v-model="name" placeholder="Enter Name"><br>
        <b>NAME:</b>{{ name }}<br><br>

        <input type="number" v-model="age" placeholder="Enter Age"><br>
        <b>AGE:</b>{{ age }}

        <p class="text-uppercase" :class="{
            'text-red' :  age < 18 && age !== '',
            // 'text-green' : age >= 18 && age !== '',
        }">
        MINOR
         <!-- {{ age < 18 ? 'MINOR' : 'ADULT' }} -->
    </p>

    <p class="text-uppercase" :class="{
            'text-green' : age >= 18 && age !== '',
        }">
        ADULT
    </p><br>

   <!-- <br> <br> <button :disabled="isError">Submit</button> -->
   <button v-on:click="handleClick">Click me</button>

    </diV>
</template>

<script>
export default {
    props : ['data'],
    data() {
        return{
            name : '',
            age : '',
            // isError: '',
                         

        }
    },

    methods : {


    },

    mounted(){



    },



};

</script>