<?php
    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Http\Request;
    use App\ToDo;
    use App\Http\Resources\ToDo as ToDoResource;

    class ToDoController extends Controller
    {
      public function __construct()
       {
       }

       /**
        * create a todo resource
        *
        * @return \Illuminate\Http\Response
        */
       public function create(Request $request)
       {
            $this->validate($request, [
                'label' => 'required',
                'details' => 'required',
            ]);

            $input = $request->input();
            $input['is_checked'] = false;
            $input['user_id'] = $request->user()->id;
            $input['due_time'] = date('Y-m-d H:i:s');
            $todo = new ToDo;
            $todo->fill($input);
            $todo->save();
            return new ToDoResource($todo);
       }

        /**
        * delete a location resource
        *
        * @return \Illuminate\Http\Response
        */
        public function delete(Request $request, $todoId)
        {
            $todo = ToDo::where([['userId', '=', $request->user()->id], ['id', '=', $todoId]]);
            if (!$todo) return response('', 403);

            ToDo::destroy($todoId);
            return response('', 200);
        }

        /**
        * get all locations 
        *
        * @return \Illuminate\Http\Response
        */
        public function get(Request $request)
        {
            $user = $request->user();
            $todos = ToDo::where([['user_id', '=', $user->id]])->get();
            return ToDoResource::collection($todos);
        }
    }    
?>