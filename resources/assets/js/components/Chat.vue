<template>

    <div>

        <div class="chatbox">
            <div class="text-center messages" v-if="messages.loading">
                <h3>Loading Messages...</h3>
            </div>
            <div class="text-center messages" v-if="messages.error">
                <h4>{{messages.error}}</h4>
            </div>
            <ul class="list-unstyled clearfix messages" v-if="messages.response">
                <li v-for="message in messages.response.data"
                    :class="{'text-left' : !message.user.is_me, 'text-right mine' : message.user.is_me }">
                    {{message.text}}
                    <br>
                    <div v-if="message.file != null">
                        <img v-if="message.file_type === 'image'" :src="message.file" class="img-responsive" alt="">
                        <div v-if="message.file_type === 'video'" class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" :src="message.file"></iframe>
                        </div>
                        <br>
                        <a :href="message.file">Download</a>
                    </div>

                    <small>Delivered {{message.created | from }}</small>
                </li>
            </ul>
        </div>

        <div class="panel-footer">
            <div class="form-group">
                <input name="message" id="message" :placeholder="'Reply to '+ participantName" class="form-control"
                       :disabled="message.sending"
                       v-model="message.text"
                       @keyup.enter="sendMessage()">
            </div>
            <ul class="list-inline clearfix">
                <li class="pull-left">
                  <span class="btn btn-default btn-file">
                        Attach File <input type="file" @change="processFile($event)">
                  </span>
                </li>
                <li class="pull-right">
                    <button class="btn btn-primary" @click="sendMessage()" :disabled="message.sending">
                        Send Message
                    </button>
                </li>
            </ul>
        </div>

    </div>

</template>

<script>
	export default {
		props   : ['conversationId', 'participantId', 'participantName'],
		mounted()
		{
			this.getMessages();
			this.listen();
			this.scrollToEnd();

		},
		updated()
		{
			this.scrollToEnd();
		},
		data()
		{
			return {
				message  : {
					sending : false,
					text    : "",
					file    : null
				},
				messages : {
					loading    : false,
					error      : null,
					response   : null,
					paginating : false,
					page       : 1
				}
			}
		},
		methods : {
			listen()
			{
				Echo.channel(`conversation.${this.conversationId}`)
					.listen('NewMessage', (e) => {
						this.messages.response.data.push(e.message);
					});
			},

			processFile(event)
			{
				this.message.file = event.target.files[0];
			},

			sendMessage()
			{
				if (this.message.text.trim() === "" && this.message.file === false)
					return;

				this.message.sending = true;
				let data             = new FormData();
				data.append('message', this.message.text);
				data.append('file', this.message.file);

				axios.post(`/conversations/${this.conversationId}/messages`, data)
					.then(response => {
						this.message.text    = "";
						this.message.sending = false;
						if (this.messages.response.data.length > 0)
							this.messages.response.data.push(response.data.data);
						else
							this.getMessages();
						this.scrollToEnd();
					})
					.catch(error => {
						alert(error.response.data.message || 'Failed to load request. Please try again later.');
						this.message.sending = false;
					});
			},

			getMessages(paginate)
			{
				this.messages.error = null;
				if (paginate) {
					this.messages.page++;
					this.messages.paginating = true;
				} else {
					this.messages.loading = true;
				}
				axios.get(`/conversations/${this.conversationId}/messages?page=${this.messages.page}`)
					.then(response => {
						if (paginate) {
							let data = this.messages.response.data;
							response.data.data.forEach(item => data.push(item));
							this.messages.response.data      = response.data;
							this.messages.response.data.data = data;
							this.messages.paginating         = false;
							this.scrollToEnd();
						} else {
							this.messages.response = response.data;
							this.messages.response.data.reverse();
							this.messages.loading = false;
							this.scrollToEnd();
						}
					})
					.catch(error => {
						this.messages.error      = error.response.data.message || 'Failed to load request. Please try again later.';
						this.messages.loading    = false;
						this.messages.paginating = false;
					});
			},

			scrollToEnd()
			{
				var container       = document.querySelector(".chatbox");
				var scrollHeight    = container.scrollHeight;
				container.scrollTop = scrollHeight;
			},

		}
	}
</script>
