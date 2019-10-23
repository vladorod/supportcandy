
#My edits



1. Теперь можно задавать агента при создании tickets через rest api 

-- для этого в параметрах нужно указать fields_data: {"assigned_agents": "{id агента}"}

-- http://suit.ru/wp-json/supportcandy/v1/tickets/addRegisteredUserTicket?auth_user={user}&auth_token={token}&fields_data={"assigned_agents": "54"}

2. Добавлена возможность показывать агентов зарегестрированному пользователю 
