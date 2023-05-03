<template>
  <div
    class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[600px]"
  >
    <img
      :src="
        event.image_url ||
        'https://picsum.photos/600'
      "
      :alt="event.title"
      class="w-full h-48 object-cover"
    />
    <h4 class="mt-4 text-lg font-bold">{{ event.title }}</h4>

    <div class="text-sm font-bold">
        <p class="text-red-500">{{ event.starts_at }}</p>
        <p class="font-normal">${{ event.price }}</p>
    </div>

    <hr>
    <div class="inline-flex items-baseline">
        <LocationMarkerIcon class="self-center w-5 h-5 rounded-full mx-1"> </LocationMarkerIcon>
        <span>{{ event.location }}</span>
    </div>
    <hr>

    <div v-html="event.description" class="overflow-hidden flex-1"></div>

    <div class="mt-4 text-md font-bold">Attendee Limit: {{ formatAttendeeLimit(event.attendee_limit) }}</div>
    <div class="mt-4 text-md font-bold">Reserved: {{ event.created_ticket_count }}</div>
    <div class="mt-4 text-md font-bold">Reserve Until: {{ event.reservation_ends_at }}</div>

    <div class="flex justify-between items-center mt-3">
      <TButton v-if="viewOnly === false" @click="emit('reserve', event)">
        <TicketIcon class="wo-5 h-5 mr-2 " />
        Reserve
      </TButton>
    </div>
  </div>
</template>

<script setup>
import TButton from "./core/TButton.vue";
import { TicketIcon, LocationMarkerIcon } from "@heroicons/vue/outline"

const { event, viewOnly } = defineProps({
  event: Object,
  viewOnly: Boolean,
});
const emit = defineEmits(["reserve"]);

function formatAttendeeLimit(attendee_limit) {
    return attendee_limit === null ? 'Unlimited' : attendee_limit;
}
</script>

<style></style>
