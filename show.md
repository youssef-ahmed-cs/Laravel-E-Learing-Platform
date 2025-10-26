# Piston API Integration

## Overview

This Laravel application integrates with the [Piston API](https://github.com/engineer-man/piston) for code execution and
compilation. Piston is a high-performance code execution engine that supports multiple programming languages.

## What is Piston?

Piston is an open-source code execution system that allows you to run code in over 50 programming languages safely and
efficiently. It's commonly used for:

- Online code editors
- Programming tutorials and learning platforms
- Code snippet testing
- Interview platforms
- Competitive programming judges

## Features

- Execute code in 50+ programming languages
- Secure sandboxed execution environment
- Support for multiple files and stdin input
- Compile-time and runtime argument support
- Language version management

## API Endpoints

### Base URL

```
https://emkc.org/api/v2/piston
```

### Available Endpoints

#### 1. List Runtimes

Get all available programming language runtimes.

```http
GET /runtimes
```

**Response Example:**

```json
[
    {
        "language": "python",
        "version": "3.10.0",
        "aliases": [
            "py",
            "py3",
            "python3"
        ]
    },
    {
        "language": "javascript",
        "version": "18.15.0",
        "aliases": [
            "js",
            "node"
        ]
    }
]
```

#### 2. Execute Code

Execute code in a specified language.

```http
POST /execute
```

**Request Body:**

```json
{
    "language": "python",
    "version": "3.10.0",
    "files": [
        {
            "name": "main.py",
            "content": "print('Hello, World!')"
        }
    ],
    "stdin": "",
    "args": [],
    "compile_timeout": 10000,
    "run_timeout": 3000,
    "compile_memory_limit": -1,
    "run_memory_limit": -1
}
```

**Response Example:**

```json
{
    "language": "python",
    "version": "3.10.0",
    "run": {
        "stdout": "Hello, World!\n",
        "stderr": "",
        "code": 0,
        "signal": null,
        "output": "Hello, World!\n"
    }
}
```

## Integration Examples


## Supported Languages

Popular languages include:

- **Python** (3.x)
- **JavaScript** (Node.js)
- **Java**
- **C / C++**
- **C#**
- **PHP**
- **Ruby**
- **Go**
- **Rust**
- **TypeScript**
- **Kotlin**
- **Swift**
- **R**
- **Bash**
- And 35+ more languages

## Request Parameters

| Parameter              | Type    | Required | Description                                              |
|------------------------|---------|----------|----------------------------------------------------------|
| `language`             | string  | Yes      | Programming language name                                |
| `version`              | string  | Yes      | Language version                                         |
| `files`                | array   | Yes      | Array of file objects with `name` and `content`          |
| `stdin`                | string  | No       | Standard input for the program                           |
| `args`                 | array   | No       | Command line arguments                                   |
| `compile_timeout`      | integer | No       | Compilation timeout in milliseconds (default: 10000)     |
| `run_timeout`          | integer | No       | Execution timeout in milliseconds (default: 3000)        |
| `compile_memory_limit` | integer | No       | Memory limit for compilation in bytes (-1 for unlimited) |
| `run_memory_limit`     | integer | No       | Memory limit for execution in bytes (-1 for unlimited)   |

## Response Structure

```json
{
    "language": "string",
    "version": "string",
    "compile": {
        "stdout": "string",
        "stderr": "string",
        "code": "integer",
        "signal": "string|null",
        "output": "string"
    },
    "run": {
        "stdout": "string",
        "stderr": "string",
        "code": "integer",
        "signal": "string|null",
        "output": "string"
    }
}
```

## Best Practices

1. **Implement Rate Limiting**: Respect the API's rate limits to avoid being blocked
2. **Set Timeouts**: Always set reasonable timeout values
3. **Validate Input**: Sanitize user code before sending to the API
4. **Cache Runtimes**: Cache the list of available runtimes to reduce API calls
5. **Handle Errors Gracefully**: Provide meaningful error messages to users
6. **Security**: Never execute untrusted code without proper sandboxing

## Resources

- [Piston GitHub Repository](https://github.com/engineer-man/piston)
- [Piston API Documentation](https://github.com/engineer-man/piston#api)
- [Public Piston Instance](https://emkc.org/api/v2/piston)

