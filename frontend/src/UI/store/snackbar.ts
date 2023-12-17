import { defineStore } from 'pinia'
interface SnackbarMessage {
  messageType: string;
  messageContent: string;
}

export const useSnackbarStore = defineStore('snackbar', {
  state: () => ({
    messages: [] as SnackbarMessage[]
  }),
  getters: {
    messageCount: state => state.messages.length
  },
  actions: {
    addMessage (content: string, msgType: string): void {
      const messages = [...this.messages]
      messages.push(
        { messageType: msgType, messageContent: content }
      )
      this.messages = messages
    },
    consume (): SnackbarMessage | undefined {
      return this.messages.pop()
    }
  }
})
