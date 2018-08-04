USE LAIKA;

INSERT INTO
	usuarios (NOMBRE, USUARIO, CLAVE)
VALUES
	('Laika', 'laika', MD5('laika1234')),
	('Yuri Gagarin', 'yuri', MD5('yuri1234')),
	('Alan Shepard', 'alan', MD5('alan1234'));

INSERT INTO
	generos (GENERO)
VALUES
	('Biografía'), 
	('Policial'), 
	('Romántico'), 
	('Terror'), 
	('Ciencia Ficción'), 
	('Narrativa'), 
	('Infantil'), 
	('Drama'),
	('Política'), 
	('Viajes'), 
	('Otro');

INSERT INTO
	autores (AUTOR)
VALUES
	('Stephen King'), 
	('Ernest Hemingway'), 
	('Harper Lee'), 
	('George Orwell'),
	('Anne Frank'),
	('Antoine de Saint-Exupéry'),
	('Robert Musil'),
	('Albert Camus'),
	('Hermann Hesse'),
	('Jorge Luis Borges'),
	('Arthur Miller'),
	('Astrid Lindgren'),
	('Tennessee Williams'),
	('Graham Greene'),
	('Betty Smith'),
	('Robert Frost'),
	('Margaret Wise Brown'),
	('John Hersey'),
	('Otro');
	
INSERT INTO
	libros (TITULO, ANIO, DESCRIPCION, FKAUTORES, FKGENEROS)
VALUES
	('Carrie', 1974, 'A Carrie le hacen bullying en la casa y la escuela; así que un día se pudre, se vuelve loca y revienta todo.', 1, 5),
	('El viejo y el mar', 1952, 'Un viejo mala onda pelea un par de días con un pez gigante le gana pero se lo morfan los tiburones.', 2, 6),
	('Matar a un ruiseñor', 1960, 'Le hacen un juicio a un negro en Alabama sólo por ser negro. No enseña cómo matar ruiseñores.', 3, 8),
	('1984', 1949, 'Hay un chabón que quiere romper todo pero en realidad lo estaban mirando todo el tiempo. Hay una chica, pero termina todo mal.', 4, 5),
	('El diario de Anne Frank', 1947, 'Pobre Ana se la pasa metida detrás de una biblioteca sin poder hacer ruido hasta que la descubren y la matan.', 5, 1);


INSERT INTO
	comentarios (COMENTARIO, FECHA, FKUSUARIOS, FKLIBROS)
VALUES
	('Completamente de acuerdo!', '2017-11-20 00:24:22', 2, 3),
	('Guau.', '2017-11-20 00:24:22', 1, 3),
	('Guau guau gggguau guau guau guuuauauau', '2017-11-20 00:24:22', 1, 2),
	('Guaaaaau guau guau.', '2017-11-20 00:24:22', 1, 1),
	('Estos bolches no entienden nada...', '2017-11-20 00:24:22', 3, 1);