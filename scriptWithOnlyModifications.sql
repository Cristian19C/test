-- Script SQL con las modificaciones necesarias para implementar las historias de usuario
-- Corregir problemas con caracteres especiales en mysql
SET NAMES utf8;

-- 1. Modificaciones para H-6568 (Asignar proceso al hallazgo)
ALTER TABLE Hallazgo
    ADD COLUMN id_proceso_sede INT NULL AFTER id_usuario,
    ADD CONSTRAINT fk_hallazgo_sede FOREIGN KEY (id_proceso_sede) REFERENCES Proceso(id);

-- Asignar procesos como sedes a los hallazgos existentes basado en el primer proceso asociado
UPDATE Hallazgo h 
JOIN (
    SELECT id_hallazgo, MIN(id_proceso) as id_proceso
    FROM Hallazgo_Proceso 
    GROUP BY id_hallazgo
) hp ON h.id = hp.id_hallazgo 
SET h.id_proceso_sede = hp.id_proceso 
WHERE h.id_proceso_sede IS NULL;

-- 2. Modificaciones para H-5995 (Cambio de estado desde el listado)
-- No requiere cambios en la estructura de la base de datos, solo en la interfaz de usuario

-- 3. Modificaciones para implementar el borrado logico en Hallazgos
ALTER TABLE Hallazgo
    ADD COLUMN isActive BOOLEAN NOT NULL DEFAULT TRUE;

-- 4. Modificaciones para H-6778 (Implementar libreria de planes de accion de incidentes en hallazgos)
-- Insertar planes de accion para hallazgos
INSERT INTO PlanAccion (descripcion, id_usuario, fecha_inicio, fecha_fin, id_estado) VALUES
    ('Mejorar control de temperatura en almacenamiento', 3, '2024-09-25', '2024-10-15', 1),
    ('Implementar nuevos protocolos de limpieza', 2, '2024-09-28', '2024-10-20', 2),
    ('Actualizar etiquetas con información bilingüe', 5, '2024-10-05', '2024-10-30', 1),
    ('Optimizar rutas de distribución', 6, '2024-09-30', '2024-10-25', 2),
    ('Revisar y mejorar procedimientos de calidad', 4, '2024-10-10', '2024-11-15', 1);

-- Asociar planes de accion a hallazgos
INSERT INTO Registro_PlanAccion (id_registro, origen_registro, id_plan_accion) VALUES
    (1, 'HALLAZGO', 11),
    (2, 'HALLAZGO', 12),
    (3, 'HALLAZGO', 13),
    (4, 'HALLAZGO', 14),
    (5, 'HALLAZGO', 15);