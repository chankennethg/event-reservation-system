<template>
    <PageComponent>
      <template v-slot:header>
        <div class="flex justify-between items-center">
          <h1 class="text-3xl font-bold text-gray-900">Events</h1>
          <TButton color="green" :to="{ name: 'EventCreate' }">
            <PlusIcon class="w-5 h-5" />
            Add new Event
          </TButton>
        </div>
      </template>
      <div v-if="events.loading" class="flex justify-center">Loading...</div>
      <div v-else-if="events.data.length">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
          <EventListItem
            v-for="(event, ind) in events.data"
            :key="event.id"
            :event="event"
            class="opacity-0 animate-fade-in-down"
            :style="{ animationDelay: `${ind * 0.1}s` }"
            @reserve="reserveEvent(event)"
          />
        </div>
        <div class="flex justify-center mt-5">
          <nav
            class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
          >
            <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
            <a
              v-for="(link, i) of events.links"
              :key="i"
              :disabled="!link.url"
              href="#"
              @click="getForPage($event, link)"
              aria-current="page"
              class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
              :class="[
                link.active
                  ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                i === 0 ? 'rounded-l-md bg-gray-100 text-gray-700' : '',
                i === events.links.length - 1 ? 'rounded-r-md' : '',
              ]"
              v-html="link.label"
            >
            </a>
          </nav>
        </div>
      </div>
      <div v-else class="text-gray-600 text-center py-16">
        No events available.
      </div>
    </PageComponent>
  </template>

  <script setup>
  import store from "../store";
  import { computed } from "vue";
  import {PlusIcon} from "@heroicons/vue/solid"
  import TButton from '../components/core/TButton.vue'
  import PageComponent from "../components/PageComponent.vue";
  import EventListItem from "../components/EventListItem.vue";

  const events = computed(() => store.state.events);

  store.dispatch("getEvents");

  /** Event Reservation */
  function reserveEvent(event) {
    if (
      confirm(
        `Are you sure you want to reserve this event?`
      )
    ) {
      store.dispatch("reserveEvent", event.uuid).then(() => {
        store.dispatch("getEvents");
        store.commit("notify", {
            type: "success",
            message: "You have successfully reserved in the event.",
        });
      });
    }
  }

  /** Pagination */
  function getForPage(event, link) {
    event.preventDefault();
    if (!link.url || link.active) {
      return;
    }

    store.dispatch("getEvents", { url: link.url });
  }
  </script>
