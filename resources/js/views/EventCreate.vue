<template>
    <PageComponent>
      <template v-slot:header>
        <div class="flex items-center justify-between">
          <h1 class="text-3xl font-bold text-gray-900"> Create an Event </h1>
        </div>
      </template>
      <form @submit.prevent="createEvent" class="animate-fade-in-down">
        <Alert
          v-if="Object.keys(errors).length"
          class="flex-col items-stretch text-sm"
        >
          <div v-for="(field, i) of Object.keys(errors)" :key="i">
            <div v-for="(error, ind) of errors[field] || []" :key="ind">
              * {{ error }}
            </div>
          </div>
        </Alert>

        <div class="shadow sm:rounded-md sm:overflow-hidden">
          <!-- Event Fields -->
          <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

            <!-- Title -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
              <input
                type="text"
                name="title"
                id="title"
                v-model="model.title"
                autocomplete="event_title"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                required
              />
            </div>
            <!--/ Title -->

            <!-- Description -->
            <div>
              <label for="about" class="block text-sm font-medium text-gray-700">Description</label>
              <div class="mt-1">
                <textarea
                  id="description"
                  name="description"
                  rows="3"
                  v-model="model.description"
                  autocomplete="event_description"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                  placeholder="Describe your event"
                  required
                />
              </div>
            </div>
            <!-- Description -->

            <!-- Location -->
            <div>
              <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
              <input
                type="text"
                name="location"
                id="location"
                v-model="model.location"
                autocomplete="event_location"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                required
              />
            </div>
            <!--/ Location -->

            <!-- Price -->
            <div>
              <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
              <input
                type="decimal"
                name="price"
                id="price"
                v-model="model.price"
                @change="formatDecimal"
                autocomplete="event_price"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                required
              />
            </div>
            <!--/ Price -->

            <!-- Attendee Limit -->
            <div>
              <label for="attendee_limit" class="block text-sm font-medium text-gray-700">Attendee Limit</label>
              <input
                type="number"
                name="attendee_limit"
                id="attendee_limit"
                v-model="model.attendee_limit"
                autocomplete="event_attendee_limit"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              />
            </div>
            <!--/ Attendee Limit -->

            <!-- Event Start Date -->
            <div>
              <label
                for="starts_at"
                class="block text-sm font-medium text-gray-700"
                >Event Start Date</label
              >
              <input
                type="datetime-local"
                name="starts_at"
                id="starts_at"
                v-model="model.starts_at"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              />
            </div>
            <!--/ Event Start Date -->

            <!-- Event End Date -->
                        <div>
              <label
                for="ends_at"
                class="block text-sm font-medium text-gray-700"
                >Event End Date</label
              >
              <input
                type="datetime-local"
                name="ends_at"
                id="ends_at"
                v-model="model.ends_at"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              />
            </div>
            <!--/ Event End Date -->

            <!-- Reservation Start Date -->
            <div>
              <label
                for="reservation_starts_at"
                class="block text-sm font-medium text-gray-700"
                >Reservation Start Date</label
              >
              <input
                type="datetime-local"
                name="reservation_starts_at"
                id="reservation_starts_at"
                v-model="model.reservation_starts_at"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              />
            </div>
            <!--/ Reservation Start Date -->

            <!-- Reservation Start Date -->
            <div>
              <label
                for="reservation_ends_at"
                class="block text-sm font-medium text-gray-700"
                >Reservation End Date</label
              >
              <input
                type="datetime-local"
                name="reservation_ends_at"
                id="reservation_ends_at"
                v-model="model.reservation_ends_at"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              />
            </div>
            <!--/ Reservation Start Date -->
          </div>
          <!--/ Event Fields End -->

          <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <TButton>
              <SaveIcon class="w-5 h-5 mr-2" />
              Create
            </TButton>
          </div>
        </div>
      </form>
    </PageComponent>
  </template>

  <script setup>
  import { ref } from "vue";
  import { useRouter } from "vue-router";
  import { SaveIcon } from '@heroicons/vue/solid'
  import { format, parseISO } from 'date-fns';
  import store from "../store";
  import PageComponent from "../components/PageComponent.vue";
  import TButton from "../components/core/TButton.vue";
  import Alert from "../components/Alert.vue";

  const router = useRouter();

  // Create empty event
  let model = ref({
    title: '',
    description: null,
    location: '',
    price: '',
    attendee_limit: null,
    starts_at: null,
    ends_at: null,
    reservation_starts_at: null,
    reservation_ends_at: null
  });

  const loading = ref(false);
  const errors = ref({});

  /**
   * Create Event
   */
  function createEvent() {
    let action = "created";
    let dateFormat = 'yyyy-MM-dd HH:mm:ss';
    model.value.starts_at = format(parseISO(model.value.starts_at), dateFormat);
    model.value.ends_at = format(parseISO(model.value.ends_at), dateFormat);
    model.value.reservation_starts_at = format(parseISO(model.value.reservation_starts_at), dateFormat);
    model.value.reservation_ends_at = format(parseISO(model.value.reservation_ends_at), dateFormat);

    store
      .dispatch("createEvent", { ...model.value })
      .then(({ data }) => {
        store.commit("notify", {
          type: "success",
          message: "The event was successfully " + action,
        });
        router.push({
          name: "Events",
        });
      })
      .catch((error) => {
        loading.value = false;
        if (error.response.status === 422) {
          errors.value = error.response.data.errors;
        }
      });;

  }

  function formatDecimal() {
    model.value.price = parseFloat(model.value.price).toFixed(2);
  }
  </script>

  <style></style>
