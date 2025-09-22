# AdvancedTokenGenerator

It's used to generate **JWT tokens** for Jitsi conferences with some advanced behaviors.

It is used when the `JWT_TOKEN_MODE` environment variable is set to `advanced`.

For example :
-  it adds extra validation rules such as email verification and private room checks.
- it can add user affiliation and enable private features (recording, livestreaming).

---

##  Details

* Retrieves the display name (`HTTP_DISPLAYNAME`) and email (`HTTP_MAIL`).
* Add tenant info as "sub" if provided.
* You should provide an access to an external endpoint like the jicofo debug one to check the room status.

**Private rooms**:

* Only users with a valid email can start the room.
* Token generation is refused if the room has not started and the email is invalid.
* Room names must follow a specific format to ensure uniqueness and security.
---

## Conference Validation & Room Format

When using the **secure room mechanism**, both the **conference** and the **room format** are validated:

1. **Conference validation**

   * The system checks if the conference (room) has already started by querying Jicofo endpoints (`/debug`).
   * This is done to allow users to join existing rooms without restrictions.

2. **Room format validation**

   * Room names must follow this pattern:

     ```
     {roomName}__{uid}_[0-9a-f]{6}-[0-9a-f]{6}-[0-9a-f]{6}
     ```

     Example:

     ```
     mathclass__b73f6d9d2c_e4a1b2-3c4d5e-6f7a8b
     ```
   * `{roomName}`: the base conference name (e.g., `mathclass`)
   * `{uid}`: a hash generated from `roomName + email`
   * The trailing hex segments ensure uniqueness.

3. **Email-based validation**

   * The system re-generates the UID using:

     ```php
     AdvancedTokenGenerator::generateId($roomName . $email, 12)
     ```
   * If the computed UID doesn’t match the one in the room name, the user cannot create the room.
