import { createRouter, createWebHistory } from 'vue-router';
import PetList from '../components/PetList.vue';
import PetForm from '../components/PetForm.vue'; // Assuming you have a Pet.vue component

const routes = [
    {
        path: '/',
        name: 'Home',
        component: PetList,
    },
    {
        path: '/pet/create',
        name: 'createPet',
        component: PetForm,
    },
    {
        path: '/pet/:id',
        name: 'Pet',
        component: PetForm,
        props: true, // To pass the pet id as a prop to Pet component
    },
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default router;
