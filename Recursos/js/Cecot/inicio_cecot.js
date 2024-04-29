document.addEventListener('DOMContentLoaded', function () {
    var ctxProd = document.getElementById('productosMasVendidos').getContext('2d');
    var chartProductos = new Chart(ctxProd, {
        type: 'bar', // Este es un gráfico de barras, puedes cambiar el tipo de gráfico según necesites
        data: {
            labels: ['Abrazadera pvc', 'Kit de herramientas', 'Cinta Metrica'], // Cambia esto por los nombres de tus productos
            datasets: [{
                label: 'Ventas',
                backgroundColor: [ '#C93E07', '#00B207', '#FABF15'],
                data: [50, 70, 60] // Aquí irían las ventas de cada producto
            }]
        }
    });
});
