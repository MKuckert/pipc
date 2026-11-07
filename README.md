# PIPC – PHP Inter-Process Communication (archived)

> **Archived, unmaintained.** This is a 2010 prototype written for **PHP 5.3**.
> It is published for historical/reference purposes only and does not run on
> modern PHP without changes. For real-world use see
> [symfony/process](https://github.com/symfony/process) or
> [amphp/parallel](https://github.com/amphp/parallel).

PIPC was an experiment to let a PHP process spawn child PHP processes and talk
to them through their stdin/stdout pipes using a small, framed binary message
protocol – with the goal of remote method invocation in worker processes.

## Contents

| Path | Description |
|---|---|
| `src/Pipc/Process.php` | Wrapper around `proc_open()` & co.: spawn a process, access its pipes, close it and read the exit code |
| `src/Pipc/PhpProcess.php` | `Process` specialised for running a PHP script with a given PHP binary |
| `src/Pipc/Protocol.php` | Protocol constants (begin marker `0x50495043` = `"PIPC"`, 12-byte header) |
| `src/Pipc/Protocol/Writer.php`, `Reader.php` | Write/read framed messages (marker, sequence number, type, length, serialized payload) on a stream |
| `src/Pipc/Protocol/MessageRegistry.php` | Maps message type ids to message classes |
| `src/Pipc/Protocol/Messages/*` | `InitialMessage` (handshake), `MethodInvocationRequestMessage` (class, method, args), `UnknownMessage` |
| `src/Pipc/Protocol/Serializer/*` | Pluggable payload serializers (JSON) and a factory |
| `src/Zend/Json*` | Bundled copy of Zend Framework 1 `Zend_Json` (New BSD License, © Zend Technologies) |
| `test/` | PHPUnit 3.x tests |

## State

The code was a work in progress and was abandoned in the middle of a refactoring:

- Some classes were already migrated to PHP 5.3 namespaces (`Pipc\Process`,
  `Pipc\Protocol\Reader`, …), others still use PEAR-style names
  (`Pipc_Protocol_Serializer_Json`, `Pipc_Protocol_Messages_*`).
- `Reader`/`Writer` reference `Serializer\ISerializer` / `Serializer\JsonSerializer`,
  which do not exist under these names.
- The process tests contain hard-coded Windows paths to a PHP binary.
- The bundled `Zend_Json` uses `$string{$i}` syntax, which was removed in PHP 8.

## License

New BSD License – see [LICENSE](LICENSE). The bundled `src/Zend` files are part of
Zend Framework 1 and are licensed under the
[New BSD License](https://framework.zend.com/license) by Zend Technologies USA, Inc.
