<?
class GlobalAdmin{

	function add_school($name_school_text, $address_school, $surname_dir_school,  $email_school_text, $phone_school){//добавление школы
		Global $link;
		if($name_school_text && $address_school && $surname_dir_school && $email_school_text && $phone_school){
			$name_school=$link->quote($name_school_text);
			$address_school=$link->quote($address_school);
			$surname_dir_school=$link->quote($surname_dir_school);
			$email_school=$link->quote($email_school_text);
			$phone_school=$link->quote($phone_school);
			$sql="INSERT INTO schools(name_school, address_school, surname_dir_school, email_school, phone_school) 
			VALUES ($name_school,$address_school,$surname_dir_school,$email_school,$phone_school)";
			$link->exec($sql);
			$password_text=md5(uniqid(rand(),true));
			$password=$link->quote($password_text);
			$name_admin="Администратор школы $name_school_text";
			$name_admin=$link->quote($name_admin);
			$sql="SELECT id id_school FROM schools order by id desc limit 0, 1";
			$res=$link->query($sql);
			$row=$res->fetch(PDO::FETCH_ASSOC);
			$id_school=$row['id_school'];
			$id_school=$link->quote($id_school);
			$sql="
			INSERT INTO users_school_admins(email, name, phone, id_school) VALUES ($email_school, $name_admin, $phone_school, $id_school)
			";
			$link->exec($sql);
			$sql="SELECT id id_user_table FROM users_school_admins order by id desc limit 0, 1";
			$res=$link->query($sql);
			$row=$res->fetch(PDO::FETCH_ASSOC);
			$id_user_table=$row['id_user_table'];
			$id_user_table=$link->quote($id_user_table);
			$sql="
			INSERT INTO users(login, password, id_user, permission) VALUES ($email_school, $password, $id_user_table, 2)";
			$link->exec($sql);
			$_SESSION['msg']="<font color='green'>Школа успешно добавлена!<br>Данные глобального администратора:<br>Логин: $email_school_text<br>Пароль: $password_text</font>";
			return true;
		}
		else{
			return false;
		}
	}
	function first_start($id_school){//добавление школы
		Global $link;
		
		$sql="
			CREATE TABLE `classes` (
			  `id` int(255) NOT NULL,
			  `id_teacher` int(255) NOT NULL,
			  `id_school` int(255) NOT NULL,
			  `number_class` int(255) NOT NULL,
			  `leter_class` varchar(255) COLLATE utf8_unicode_ci NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


			-- --------------------------------------------------------

			--
			-- Структура таблицы `lessons`
			--

			CREATE TABLE `lessons` (
			  `id` int(255) NOT NULL,
			  `name` varchar(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			--
			-- Структура таблицы `lesson_teacher_class`
			--

			CREATE TABLE `lesson_teacher_class` (
			  `id` int(255) NOT NULL,
			  `id_teacher` int(255) NOT NULL,
			  `id_lesson` int(255) NOT NULL,
			  `id_class` int(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			-- --------------------------------------------------------

			--
			-- Структура таблицы `marks`
			--

			CREATE TABLE `marks` (
			  `id` int(255) NOT NULL,
			  `id_student` int(255) NOT NULL,
			  `id_teacher` int(255) NOT NULL,
			  `id_lesson` int(255) NOT NULL,
			  `mark` int(255) NOT NULL,
			  `description` text NOT NULL,
			  `date` varchar(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			--
			-- Структура таблицы `news_classes`
			--

			CREATE TABLE `news_classes` (
			  `id` int(255) NOT NULL,
			  `header` varchar(255) NOT NULL,
			  `new` text NOT NULL,
			  `autor` int(255) NOT NULL,
			  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `id_class` int(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			--
			-- Структура таблицы `schedule`
			--

			CREATE TABLE `schedule` (
			  `id` int(255) NOT NULL,
			  `id_lesson` int(255) NOT NULL,
			  `number_lesson` int(1) NOT NULL,
			  `day` int(1) NOT NULL,
			  `id_class` int(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			-- -------------------------------------------------------
			--
			-- Структура таблицы `users`
			--

			CREATE TABLE `users` (
			  `id` int(255) NOT NULL,
			  `login` varchar(255) NOT NULL,
			  `password` varchar(255) NOT NULL,
			  `id_user` int(255) NOT NULL,
			  `permission` int(2) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			--
			-- Структура таблицы `users_school_admins`
			--

			CREATE TABLE `users_school_admins` (
			  `id` int(255) NOT NULL,
			  `email` varchar(255) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `phone` varchar(255) NOT NULL,
			  `id_school` int(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			--
			-- Дамп данных таблицы `users_school_admins`
			--

			-- --------------------------------------------------------

			--
			-- Структура таблицы `users_students`
			--

			CREATE TABLE `users_students` (
			  `id` int(255) NOT NULL,
			  `email` varchar(255) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `phone` varchar(255) NOT NULL,
			  `id_class` int(255) NOT NULL,
			  `id_parent` int(255) NOT NULL,
			  `id_school` int(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			--
			-- Структура таблицы `users_teachers`
			--

			CREATE TABLE `users_teachers` (
			  `id` int(255) NOT NULL,
			  `email` varchar(255) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `phone` varchar(255) NOT NULL,
			  `id_base_lesson` int(255) NOT NULL,
			  `employment` int(255) NOT NULL,
			  `id_school` int(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			--
			-- Индексы сохранённых таблиц
			--

			--
			-- Индексы таблицы `classes`
			--
			ALTER TABLE `classes`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `lessons`
			--
			ALTER TABLE `lessons`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `lesson_teacher_class`
			--
			ALTER TABLE `lesson_teacher_class`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `marks`
			--
			ALTER TABLE `marks`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `news_classes`
			--
			ALTER TABLE `news_classes`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `schedule`
			--
			ALTER TABLE `schedule`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `users`
			--
			ALTER TABLE `users`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `users_school_admins`
			--
			ALTER TABLE `users_school_admins`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `users_students`
			--
			ALTER TABLE `users_students`
			  ADD PRIMARY KEY (`id`);

			--
			-- Индексы таблицы `users_teachers`
			--
			ALTER TABLE `users_teachers`
			  ADD PRIMARY KEY (`id`);

			--
			-- AUTO_INCREMENT для сохранённых таблиц
			--

			--
			-- AUTO_INCREMENT для таблицы `classes`
			--
			ALTER TABLE `classes`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
			--
			-- AUTO_INCREMENT для таблицы `lesson_teacher_class`
			--
			ALTER TABLE `lesson_teacher_class`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
			--
			-- AUTO_INCREMENT для таблицы `marks`
			--
			ALTER TABLE `marks`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
			--
			-- AUTO_INCREMENT для таблицы `news_classes`
			--
			ALTER TABLE `news_classes`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
			--
			-- AUTO_INCREMENT для таблицы `schedule`
			--
			ALTER TABLE `schedule`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
			--
			-- AUTO_INCREMENT для таблицы `users`
			--
			ALTER TABLE `users`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
			--
			-- AUTO_INCREMENT для таблицы `users_school_admins`
			--
			ALTER TABLE `users_school_admins`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
			--
			-- AUTO_INCREMENT для таблицы `users_students`
			--
			ALTER TABLE `users_students`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
			--
			-- AUTO_INCREMENT для таблицы `users_teachers`
			--
			ALTER TABLE `users_teachers`
			  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

		";

		$json_online=$this->getData(1);
		//echo $json_online;
		$json_local=json_decode($json_online,true);
		//var_dump($json_local);
		foreach($json_local['classes'] as $class){
			echo 1;
			$class['leter_class']=$link->quote($class['leter_class']);
			$str_classes.="({$class['id']},{$class['id_teacher']},$id_school,{$class['number_class']},{$class['leter_class']}),";
		}
		$str_classes = substr($str_classes,0,-1);
		if($str_classes){
			$sql="INSERT IGNORE INTO classes(id, id_teacher, id_school, number_class, leter_class) VALUES $str_classes";
			$link->exec($sql);
			echo $sql;
		}

		foreach($json_local['users_students'] as $student){
			$student['email']=$link->quote($student['email']);
			$student['name']=$link->quote($student['name']);
			$student['phone']=$link->quote($student['phone']);

			$str_student.="({$student['id']},{$student['email']},{$student['name']},{$student['phone']},{$student['id_class']},{$student['id_parent']},$id_school),";
		}
		$str_student = substr($str_student,0,-1);
		if($str_student){
			$sql="INSERT IGNORE INTO users_students(id, email, name, phone, id_class, id_parent, id_school) VALUES $str_student";
			//echo $sql;
			$link->exec($sql);
			//echo $sql;
		}


		foreach($json_local['users_teachers'] as $teacher){
			$teacher['email']=$link->quote($teacher['email']);
			$teacher['name']=$link->quote($teacher['name']);
			$teacher['phone']=$link->quote($teacher['phone']);

			$str_teacher.="({$teacher['id']},{$teacher['email']},{$teacher['name']},{$teacher['phone']},1,0,$id_school),";
		}
		$str_teacher = substr($str_teacher,0,-1);
		if($str_teacher){
			$sql="INSERT IGNORE INTO users_teachers(id, email, name, phone, id_base_lesson, employment, id_school) VALUES $str_teacher";
			//echo $sql;
			$link->exec($sql);
			//echo $sql;
		}

		/*foreach($json_local['schedule'] as $schedule){
			$schedule['email']=$link->quote($schedule['email']);
			$schedule['name']=$link->quote($schedule['name']);
			$schedule['phone']=$link->quote($schedule['phone']);

			$str_schedule.="({$schedule['id']},{$schedule['email']},{$schedule['name']},{$schedule['phone']},1,0,$id_school),";
		}
		$str_teacher = substr($str_teacher,0,-1);
		if($str_teacher){
			$sql="INSERT IGNORE INTO users_teachers(id, email, name, phone, id_base_lesson, employment, id_school) VALUES $str_teacher";
			//echo $sql;
			$link->exec($sql);
			//echo $sql;
		}*/
		foreach($json_local['marks'] as $marks){
			$marks['description']=$link->quote($marks['description']);
			$marks['date']=$link->quote($marks['date']);

			$str_marks.="({$marks['id']},{$marks['id_student']},{$marks['id_teacher']},{$marks['id_lesson']},{$marks['mark']},{$marks['description']},{$marks['date']}),";
		}
		$str_marks = substr($str_marks,0,-1);
		if($str_marks){
			$sql="INSERT IGNORE INTO marks(id, id_student, id_teacher, id_lesson, mark, description, date) VALUES $str_marks";
			//echo $sql;
			$link->exec($sql);
			//echo $sql;
		}

		foreach($json_local['lesson_teacher_class'] as $lesson){
			$str_lesson_teacher_class.="({$lesson['id']},{$lesson['id_teacher']},{$lesson['id_lesson']},{$lesson['id_class']}),";
		}
		$str_lesson_teacher_class = substr($str_lesson_teacher_class,0,-1);
		if($str_lesson_teacher_class){
			$sql="INSERT IGNORE INTO lesson_teacher_class(id, id_teacher, id_lesson, id_class) VALUES $str_lesson_teacher_class";
			echo $sql;
			$link->exec($sql);
			//echo $sql;
		}

		foreach($json_local['schedule'] as $schedule){
			$str_schedule.="({$schedule['id']},{$schedule['id_lesson']},{$schedule['number_lesson']},{$schedule['day']},{$schedule['id_class']}),";
		}
		$str_schedule= substr($str_schedule,0,-1);
		if($str_schedule){
			$sql="INSERT IGNORE INTO schedule(id, id_lesson, number_lesson, day, id_class) VALUES $str_schedule";
			echo $sql;
			$link->exec($sql);
			//echo $sql;
		}

		foreach($json_local['news_classes'] as $news_classes){
			$str_news_classes.="({$news_classes['id']},{$news_classes['header']},{$news_classes['new']},{$news_classes['autor']},{$news_classes['date']},{$news_classes['id_class']}),";
		}
		$str_news_classes = substr($str_news_classes,0,-1);
		if($str_news_classes){
			$sql="INSERT IGNORE INTO news_classes(id, header, new, autor, date, id_class) VALUES $str_news_classes";
			echo $sql;
			$link->exec($sql);
			//echo $sql;
		}

		foreach($json_local['users'] as $users){
			$users['login']=$link->quote($users['login']);
			$users['password']=$link->quote($users['password']);
			$str_users.="({$users['id']},{$users['login']},{$users['password']},{$users['id_user']},{$users['permission']}),";
		}
		$str_users = substr($str_users,0,-1);
		if($str_users){
			$sql="INSERT IGNORE INTO users(id, login, password, id_user, permission) VALUES $str_users";
			echo $sql;
			$link->exec($sql);
			//echo $sql;
		}


	}
	function getData($id_school){
		return file_get_contents("http://127.0.0.1:80/api.php?id_school=1");
	}
}
?>