<script setup lang="ts">
import { ref, computed, watch, onMounted, Ref, UnwrapRef } from 'vue';
import axios from 'axios';

interface Event {
    id: number,
    name: string,
    starts_at: string,
    capacity: number,
    tickets_left: number,
}

interface Booking {
    id: number,
    user_id: number,
    event_id: number,
}

const props = defineProps<{
    events: Event[],
}>();

const events = ref(props.events);
const bookings = ref<Booking[]>([]);
watch(()=> props.events, ()=>{
    events.value = props.events;
})

const selectedEventId = ref<number | null>(null);
const getBookingsForEvent = (eventId: number | null) => {
    if (!eventId) return;

    axios.get(`/events/${eventId}/bookings`)
        .then((response: any)=>{
            bookings.value = response.data.bookings;
        })
}

onMounted(()=>{
    getBookingsForEvent(selectedEventId.value);
})


const handleSelectEvent = (id: number) => {
    if (selectedEventId.value === id) {
        selectedEventId.value = null
    } else {
        selectedEventId.value = id
    }

    getBookingsForEvent(selectedEventId.value);
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 p-6 md:p-12">
        <div class="max-w-7xl mx-auto">
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestiune Evenimente și Rezervări</h1>
                <p class="text-slate-500 mt-2">Selectează un eveniment pentru a vizualiza rezervările asociate.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col h-[600px]">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-5 bg-indigo-600 rounded-full"></span>
                            Evenimente
                        </h2>
                        <span class="text-xs font-medium px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full">
              {{ events.length }} în total
            </span>
                    </div>

                    <div class="space-y-3 overflow-y-auto flex-1 pr-2 custom-scrollbar">
                        <div
                            v-for="event in events"
                            :key="event.id"
                            @click="handleSelectEvent(event.id)"
                            :class="[
                'p-4 rounded-xl border transition-all duration-200 cursor-pointer text-left',
                selectedEventId === event.id
                  ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-600/20'
                  : 'border-slate-100 hover:border-slate-300 hover:bg-slate-50/50'
              ]"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-slate-900" :class="{ 'text-indigo-900': selectedEventId === event.id }">
                                    {{ event.name }}
                                </h3>
                                <span
                                    :class="[
                    'text-xs font-medium px-2 py-0.5 rounded-md',
                    event.tickets_left > 300 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'
                  ]"
                                >
                  {{event.tickets_left}} / {{ event.capacity }}
                </span>
                            </div>

                            <div class="flex flex-wrap gap-y-1 gap-x-4 text-sm text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ event.starts_at }}</span>
                                </div>
<!--                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>da</span>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col h-[600px]">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-5 bg-emerald-600 rounded-full"></span>
                            Rezervări Asociate
                        </h2>
                        <span
                            v-if="selectedEventId"
                            class="text-xs font-medium px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-100"
                        >
              {{ bookings?.length }} rezervări
            </span>
                    </div>

                    <div v-if="!selectedEventId" class="flex-1 flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Nu a fost selectat niciun eveniment</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs">Alege un eveniment din panoul din stânga pentru a-i vedea lista completă de participanți.</p>
                    </div>

                    <div v-else-if="bookings?.length === 0" class="flex-1 flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Zero rezervări găsite</p>
                        <p class="text-xs text-slate-400 mt-1">Acest eveniment nu are nicio rezervare înregistrată momentan.</p>
                    </div>

                    <div v-else class="space-y-3 overflow-y-auto flex-1 pr-2 custom-scrollbar">
                        <div
                            v-for="booking in bookings"
                            :key="booking.id"
                            class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between hover:bg-slate-50 transition-colors"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm">
                                    1
                                </div>
<!--                                <div>
                                    <h4 class="font-medium text-slate-800 text-sm md:text-base">{{ booking.userName }}</h4>
                                    <p class="text-xs text-slate-500">{{ booking.userEmail }}</p>
                                </div>-->
                            </div>

                            <div class="text-right">
<!--                <span class="block text-xs font-semibold text-slate-700 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm">
                  Locuri: {{ booking.seats }}
                </span>-->
<!--
                                <span class="block text-[10px] text-slate-400 mt-1">{{ booking.bookedAt }}</span>
-->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
