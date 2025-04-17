const express = require('express');
const mysql = require('mysql2');

const app = express();
app.use(express.json()); // Middleware para manejar JSON

// Configuración de la conexión a MySQL
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root', // Cambia esto por tu usuario de MySQL
  password: '1234', // Cambia esto por tu contraseña
  database: 'encuesta' // Cambia esto por tu base de datos
});

// Endpoint para insertar datos en la tabla "respuestas"
app.post('/respuestas', (req, res) => {
  const { respuesta, pregunta, encuesta, encuestado } = req.body;

  if (!respuesta || !pregunta || !encuesta || !encuestado) {
    return res.status(400).send({ error: 'Todos los campos son requeridos' });
  }

  const sql = 'INSERT INTO respuestas (respuesta, pregunta, encuesta, encuestado) VALUES (?, ?, ?, ?)';
  
  connection.query(sql, [respuesta, pregunta, encuesta, encuestado], (err, results) => {
    if (err) {
      console.error('Error al insertar los datos:', err);
      return res.status(500).send({ error: 'Error en el servidor' });
    }

    res.status(201).send({ message: 'Datos insertados correctamente', id: results.insertId });
  });
});

// Iniciar el servidor
const PORT = 3000;
app.listen(PORT, () => {
  console.log(`Servidor ejecutándose en http://localhost:${PORT}`);
});