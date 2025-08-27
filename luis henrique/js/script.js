document.addEventListener('DOMContentLoaded', () => {
  const conteudo = document.getElementById('conteudo-principal');

  // Função para carregar conteúdo das páginas
  function loadPage(page) {
    fetch(`pages/${page}.html`)
      .then(res => res.text())
      .then(html => {
        conteudo.innerHTML = html;

        // Se tiver formulário de avaliação
        const form = document.getElementById('avaliacaoForm');
        if(form){
          form.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Avaliação salva/enviada! (simulação)');
          });
        }
      })
      .catch(err => {
        conteudo.innerHTML = `<p>Página não encontrada: ${page}</p>`;
      });
  }

  // Menu Admin
  document.querySelectorAll('#admin-menu a').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const page = e.target.dataset.page;
      loadPage(page);
    });
  });

  // Menu Jurado
  document.querySelectorAll('#jurado-menu a').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const page = e.target.dataset.page;
      loadPage(page);
    });
  });

  // Carregar página inicial
  loadPage('admin-dashboard');
});
