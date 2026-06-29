-- Fidelidade dos clientes
insert into fidelidades ( usuario_id, pontos) 
select id,0
from usuarios
where perfil = 'Cliente'
on conflict (usuario_id) do nothing;