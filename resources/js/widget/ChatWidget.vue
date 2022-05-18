<template>
  <div>
    <!-- TO DO: Styling for the End Conversation button. -->
    <h2>
      Conversation
      <!--<button id=exit-chat onClick="endConversation()"><i class="material-icons" style="font-size:20px;">logout</i></button>-->
    </h2>

    <!--CHAT MESSAGES BOX-->
    <div id="messages" class="content">
      <div class="messages">
        <div class="message sent-message">
          <div class="name">You</div>
          <div class="text">
            Hello, how are you? I need to talk to an agent.
          </div>
        </div>
        <div class="update">Agent X joined the conversation</div>
        <div class="message received-message">
          <div class="name">Agent X</div>
          <div class="text">Hi. I am an agent. How may I help you?</div>
        </div>
      </div>

      <!--INPUT MESSAGE BOX-->
      <form class="chatbox-input" @submit.prevent="alert(message)">
        <input
          v-model="message"
          type="text"
          name="message"
          placeholder="Enter your message..."
          class="form-control"
        />
      </form>
    </div>
  </div>
</template>

<style scoped>

</style>

<script>
export default {
  data() {
    return {
      isVisible: true,
      message: '',
    };
  },
  methods: {
      alert(name) {
          window.alert(name);
      }
  }
};
</script>