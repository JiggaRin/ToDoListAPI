<?php

namespace App\Http\Controllers;

/**
 * @OA\Schema(
 *       schema="Task",
 *       type="object",
 *       description="Represents tasks with its details and subtasks.",
 *       @OA\Property(
 *           property="id",
 *           type="integer",
 *           description="The unique identifier of the task."
 *       ),
 *       @OA\Property(
 *            property="user_id",
 *            type="integer",
 *            description="ID of the user."
 *       ),
 *       @OA\Property(
 *            property="parent_id",
 *            type="integer",
 *            description="The ID of the parent task, if any."
 *       ),
 *       @OA\Property(
 *           property="title",
 *           type="string",
 *           description="The title of the task."
 *       ),
 *       @OA\Property(
 *            property="description",
 *            type="string",
 *            description="A detailed description of the task."
 *       ),
 *       @OA\Property(
 *           property="status",
 *           type="string",
 *           description="The current status of the task."
 *       ),
 *       @OA\Property(
 *           property="priority",
 *           type="integer",
 *           description="The priority level of the task."
 *       ),
 *       @OA\Property(
 *           property="completed_at",
 *           type="string",
 *           format="date-time",
 *           description="The date and time when the task was completed."
 *       ),
 *       @OA\Property(
 *           property="subtasks",
 *           type="array",
 *           @OA\Items(
 *               ref="#/components/schemas/Task"
 *           ),
 *           description="A list of subtasks associated with this task."
 *       ),
 *       required={"id", "title"}
 * )
 *
 * @OA\Schema(
 *      schema="CreateTaskDTO",
 *      type="object",
 *      description="Data Transfer Object for creating a new task",
 *      @OA\Property(
 *          property="title",
 *          type="string",
 *          description="The title of the task",
 *          example="Buy groceries"
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string",
 *          description="The status of the task",
 *          example="todo",
 *          default="todo"
 *      ),
 *      @OA\Property(
 *          property="priority",
 *          type="integer",
 *          description="The priority level of the task",
 *          example=1,
 *          default=1
 *      ),
 *      @OA\Property(
 *          property="parent_id",
 *          type="integer",
 *          description="The ID of the parent task, if any",
 *          nullable=true,
 *          example=null
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          description="A detailed description of the task",
 *          nullable=true,
 *          example="Buy milk, eggs, and bread"
 *      ),
 *      @OA\Property(
 *          property="completed_at",
 *          type="string",
 *          format="date-time",
 *          description="The date and time when the task was completed",
 *          nullable=true,
 *          example="2024-08-01T12:00:00Z"
 *      ),
 *      required={"title"}
 * )
 *
 * @OA\Schema(
 *      schema="UpdateTaskDTO",
 *      type="object",
 *      description="Data Transfer Object for updating an existing task",
 *      @OA\Property(
 *          property="title",
 *          type="string",
 *          description="The title of the task",
 *          nullable=true,
 *          example="Buy groceries"
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string",
 *          description="The status of the task",
 *          nullable=true,
 *          example="todo",
 *          default="todo"
 *      ),
 *      @OA\Property(
 *          property="priority",
 *          type="integer",
 *          description="The priority level of the task",
 *          nullable=true,
 *          example=1,
 *          default=1
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          description="A detailed description of the task",
 *          nullable=true,
 *          example="Buy milk, eggs, and bread"
 *      ),
 *      @OA\Property(
 *          property="parent_id",
 *          type="integer",
 *          description="The ID of the parent task, if any",
 *          nullable=true,
 *          example=null
 *      ),
 *      @OA\Property(
 *          property="completed_at",
 *          type="string",
 *          format="date-time",
 *          description="The date and time when the task was completed",
 *          nullable=true,
 *          example="2024-08-01T12:00:00Z"
 *      )
 * )
 * @OA\Schema(
 *       schema="ChangeTaskStatusDTO",
 *       type="object",
 *       description="Data Transfer Object for changing the status of a task",
 *       @OA\Property(
 *           property="status",
 *           type="string",
 *           description="The new status of the task",
 *           example="done"
 *       ),
 *       @OA\Property(
 *           property="completed_at",
 *           type="string",
 *           format="date-time",
 *           description="The date and time when the task was completed",
 *           nullable=true,
 *           example="2024-08-01T12:00:00Z"
 *       ),
 *       required={"status"}
 *  )
 *
 * @OA\Schema(
 *     schema="TaskResponseDTO",
 *     type="object",
 *     description="The object with response after proceeding requests"
 *  )
 *
 * @OA\Schema(
 *     schema="CreateTaskRequest",
 *     type="object",
 *      @OA\Property(
 *          property="title",
 *          type="string",
 *          description="The title of the task",
 *          maxLength=255
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          description="A detailed description of the task",
 *          nullable=true,
 *          maxLength=255
 *      ),
 *      @OA\Property(
 *          property="priority",
 *          type="integer",
 *          description="The priority level of the task",
 *          nullable=true,
 *          enum={1,2,3,4,5}
 *      ),
 * @OA\Property(
 *          property="status",
 *          type="string",
 *          description="The current status of the task",
 *          nullable=true,
 *          enum={"todo","done"}
 *      ),
 * @OA\Property(
 *          property="parent_id",
 *          type="integer",
 *          description="The ID of the parent task, if any",
 *          nullable=true
 *      )
 *  )
 *     required={"title"}
 * )
 *
 * @OA\Schema(
 *      schema="UpdateTaskRequest",
 *      type="object",
 *      description="Request body for updating a task",
 *      @OA\Property(
 *          property="title",
 *          type="string",
 *          description="The title of the task",
 *          nullable=true,
 *          maxLength=255
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          description="A detailed description of the task",
 *          nullable=true,
 *          maxLength=255
 *      ),
 *      @OA\Property(
 *          property="priority",
 *          type="integer",
 *          description="The priority level of the task",
 *          nullable=true,
 *          enum={1,2,3,4,5}
 *      ),
 *      @OA\Property(
 *          property="status",
 *          type="string",
 *          description="The current status of the task",
 *          nullable=true,
 *          enum={"todo","done"}
 *      ),
 *      @OA\Property(
 *          property="parent_id",
 *          type="integer",
 *          description="The ID of the parent task, if any",
 *          nullable=true
 *      )
 *  )
 *
 * @OA\Schema(
 *       schema="ChangeTaskStatusRequest",
 *       type="object",
 *       description="Request body for changing the status of a task",
 *       @OA\Property(
 *           property="status",
 *           type="string",
 *           description="The new status of the task",
 *           enum={"todo", "done"},
 *           example="done"
 *       ),
 *       required={"status"}
 *  )
 *
 * @OA\Schema(
 *      schema="ValidationError",
 *      type="object",
 *      description="Validation error response",
 *      @OA\Property(
 *          property="message",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="errors",
 *          type="object",
 *          description="Validation errors for each field.",
 *          @OA\AdditionalProperties(
 *              type="array",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */
abstract class Controller
{
    //
}
