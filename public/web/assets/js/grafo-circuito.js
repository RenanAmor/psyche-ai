/**
 * Grafo do Circuito/Trajeto (Sprint 19, "Camada de Visualização Gráfica").
 * Script deliberadamente burro: só busca o JSON já pronto do servidor e
 * desenha — nenhuma regra de negócio, nenhuma inferência sobre o dado.
 * Não é a cadeia de significantes lacaniana (Ontologia-Lacan.md §4) — é
 * só a mesma lista de "Sessão -> Sessão" da página, como grafo. Se o CDN
 * do D3 falhar ou o fetch falhar, o container fica vazio e a lista de
 * texto acima (sempre renderizada pelo servidor) continua sendo a fonte
 * de dado.
 */
(function () {
    var container = document.getElementById('grafo-circuito');

    if (!container || typeof window.d3 === 'undefined') {
        return;
    }

    fetch(container.dataset.endpoint)
        .then(function (resposta) { return resposta.json(); })
        .then(function (grafo) {
            if (!grafo.sucesso || !grafo.nos || grafo.nos.length === 0) {
                return;
            }

            desenhar(container, grafo.nos, grafo.arestas);
        })
        .catch(function () {
            // Fallback silencioso: lista textual do circuito continua visível.
        });

    function desenhar(container, nos, arestas) {
        var d3 = window.d3;
        var margem = { topo: 30, base: 30, esquerda: 60, direita: 60 };
        var altura = 160;
        var nosPorId = {};

        var nosOrdenados = nos.slice().sort(function (a, b) {
            return a.momento < b.momento ? -1 : a.momento > b.momento ? 1 : 0;
        });

        var largura = Math.max(
            container.clientWidth || 600,
            margem.esquerda + margem.direita + nosOrdenados.length * 120
        );

        var espaco = nosOrdenados.length > 1
            ? (largura - margem.esquerda - margem.direita) / (nosOrdenados.length - 1)
            : 0;

        nosOrdenados.forEach(function (no, indice) {
            no.x = margem.esquerda + indice * espaco;
            no.y = altura / 2;
            nosPorId[no.id] = no;
        });

        var svg = d3.select(container)
            .append('svg')
            .attr('viewBox', '0 0 ' + largura + ' ' + altura)
            .attr('width', '100%')
            .attr('height', altura)
            .attr('role', 'img')
            .attr('aria-label', 'Grafo do circuito/trajeto: sessões e recorrências entre elas.');

        var grupoArestas = svg.append('g').attr('class', 'arestas');

        arestas.forEach(function (aresta, indice) {
            var origem = nosPorId[aresta.origem];
            var destino = nosPorId[aresta.destino];

            if (!origem || !destino) {
                return;
            }

            var candidataLacaniana = aresta.rotuloLacaniano !== null && aresta.rotuloLacaniano !== undefined;
            var arco = 20 + (indice % 3) * 14;
            var meioX = (origem.x + destino.x) / 2;
            var meioY = origem.y - arco;

            var caminho = grupoArestas.append('path')
                .attr('d', 'M ' + origem.x + ' ' + origem.y
                    + ' Q ' + meioX + ' ' + meioY + ' ' + destino.x + ' ' + destino.y)
                .attr('fill', 'none')
                .attr('stroke', candidataLacaniana ? '#8a4fff' : '#4a4a4a')
                .attr('stroke-width', 2)
                .attr('stroke-dasharray', candidataLacaniana ? '6 4' : null);

            caminho.append('title')
                .text(aresta.descricao + (candidataLacaniana ? ' — ' + aresta.rotuloLacaniano : ''));

            if (candidataLacaniana) {
                svg.append('text')
                    .attr('x', meioX)
                    .attr('y', meioY - 4)
                    .attr('text-anchor', 'middle')
                    .attr('font-size', '11px')
                    .attr('fill', '#8a4fff')
                    .text('estrutura candidata');
            }
        });

        var grupoNos = svg.append('g').attr('class', 'nos');

        nosOrdenados.forEach(function (no) {
            var grupo = grupoNos.append('g').attr('transform', 'translate(' + no.x + ',' + no.y + ')');

            grupo.append('circle')
                .attr('r', 8)
                .attr('fill', '#1a1a1a');

            grupo.append('title').text('Sessão ' + no.momento);

            grupo.append('text')
                .attr('y', 24)
                .attr('text-anchor', 'middle')
                .attr('font-size', '11px')
                .text(no.momento);
        });
    }
})();
