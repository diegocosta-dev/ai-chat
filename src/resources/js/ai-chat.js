(function () {
  const CHAT_KEY = 'ai_chat_history';
  const EXPIRATION_HOURS = 24;

  const sendButton = document.getElementById('ai-chat-send');
  const input = document.getElementById('ai-chat-input');
  const messages = document.getElementById('ai-chat-messages');

  // Load history from localStorage
  function loadHistory() {
    const data = localStorage.getItem(CHAT_KEY);
    if (!data) return [];
    const parsed = JSON.parse(data);
    const now = Date.now();
    if (now - parsed.timestamp > EXPIRATION_HOURS * 60 * 60 * 1000) {
      localStorage.removeItem(CHAT_KEY);
      return [];
    }
    return parsed.history || [];
  }

  // Save history to localStorage
  function saveHistory(history) {
    localStorage.setItem(CHAT_KEY, JSON.stringify({
      history,
      timestamp: Date.now()
    }));
  }

  // Render history in the chat
  function renderHistory() {
    const history = loadHistory();
    messages.innerHTML = ''; // Clean before rendering
    history.forEach(item => {
      const msg = document.createElement('div');
      msg.className = 'ai-chat-message ' + item.role;
      msg.textContent = item.content;
      messages.appendChild(msg);
    });
    messages.scrollTop = messages.scrollHeight;
  }

  // Add message to chat and save
  function addMessage(content, role) {
    const msg = document.createElement('div');
    msg.className = 'ai-chat-message ' + role;
    msg.textContent = content;
    messages.appendChild(msg);
    messages.scrollTop = messages.scrollHeight;

    const history = loadHistory();
    history.push({ content, role });
    saveHistory(history);
  }

  // Sends message to backend
  async function sendMessage() {
    const message = input.value.trim();
    if (!message) return;
    addMessage(message, 'user');
    input.value = '';

    try {
      const formData = new FormData();
      formData.append('message', message);

      const res = await fetch('/actions/ai-chat/chat/ask', {
        method: 'POST',
        headers: { 'X-CSRF-Token': CSRF_TOKEN },
        body: formData
      });

      const text = await res.text();
      console.log('Resposta bruta:', text);

      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        addMessage('Erro: resposta não é JSON.', 'bot');
        return;
      }

      addMessage(data.reply || 'Erro na resposta.', 'bot');
    } catch (err) {
      addMessage('Erro ao enviar mensagem.', 'bot');
    }
  }

  // Initialize chat
  renderHistory();

  sendButton.addEventListener('click', sendMessage);
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') sendMessage();
  });
})();
